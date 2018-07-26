<?php
declare(strict_types = 1);

namespace noximo\PHPColoredConsoleLinegraph;

use ReflectionException;

/**
 * Class LineGraph
 * @package noximo\PHPColoredConsoleLinegraph
 */
class LineGraph
{
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @var array $allSeries = [
     * 'legend' => ['series' => [1,2,3.45], 'colors' => [1,2,3], 'legend' => 'legend'];
     * ]
     */
    private $allSeries = [];
    /**
     * @var array $points = ['x' => 1, 'y' => 0.75]
     */
    private $points = [];
    /**
     * @var int
     */
    private $iteration = 0;

    /**
     * @param array $series
     * @param array $colors
     * @param string|null $legend
     *
     * @return LineGraph
     */
    public function addSeries(array $series, array $colors = [], string $legend = null): LineGraph
    {
        $seriesData = ['series' => $series, 'colors' => $colors, 'legend' => $legend];
        if ($legend) {
            $this->allSeries[$legend] = $seriesData;
        } else {
            $this->allSeries[] = $seriesData;
        }

        return $this;
    }

    /**
     * @param int $time
     * @param float $value
     *
     * @return LineGraph
     */
    public function addPoint(int $time, float $value): LineGraph
    {
        $this->points[] = ['x' => $time, 'y' => $value];

        return $this;
    }

    /**
     * @return Graph
     * @throws ColorException
     * @throws ReflectionException
     */
    public function graph(): Graph
    {
        $this->iteration++;
        $settings = $this->getSettings();
        $allSeries = $this->allSeries;

        $graph = new Graph();
        $graph->setSettings($settings);
        $graph->setIteration($this->iteration);

        $this->findMinMax($graph, $allSeries);

        $min = $graph->getMin();
        $max = $graph->getMax();
        $width = $graph->getWidth();
        $count = $graph->getWidth();

        $range = (int)max(1, abs($max - $min));
        $settings->setComputedHeight($range);

        $ratio = $settings->getHeight() / $range;
        $min2 = (int) round($min * $ratio);
        $max2 = (int) round($max * $ratio);

        $rows = max(1, abs($max2 - $min2));
        $width += $settings->getOffset();

        foreach ($allSeries as $seriesData) {
            $series = $seriesData['series'];
            $colors = $seriesData['colors'];
            $result = [];

            /** @noinspection ForeachInvariantsInspection */
            for ($i = 0; $i <= $rows; $i++) {
                $result[$i] = array_fill(0, $width, ' ');
            }

            $format = $settings->getFormat();
            for ($y = $min2; $y <= $max2; ++$y) { // axis + labels
                $rawLabel = $max - ($y - $min2) * $range / $rows;
                $label = $format($rawLabel, $settings);

                $result[$y - $min2][max($settings->getOffset() - \strlen($label), 0)] = $label;
                $result[$y - $min2][$settings->getOffset() - 1] = '┤';
            }

            $y0 = round($series[0] * $ratio) - $min2;
            $result[$rows - $y0][$settings->getOffset() - 1] = Color::colorize('┼', $colors); // first value

            for ($x = 0; $x < $count - 1; $x++) {
                if (!empty($series[$x]) && !empty($series[$x + 1])) {
                    $y0 = (int) round($series[$x] * $ratio) - $min2;
                    $y1 = (int) round($series[$x + 1] * $ratio) - $min2;
                    if ($y0 == $y1) {
                        $result[$rows - $y0][$x + $settings->getOffset()] = Color::colorize('─', $colors);
                    } else {
                        $result[$rows - $y1][$x + $settings->getOffset()] = Color::colorize(($y0 > $y1) ? '╰' : '╭', $colors);
                        $result[$rows - $y0][$x + $settings->getOffset()] = Color::colorize(($y0 > $y1) ? '╮' : '╯', $colors);
                        $from = min($y0, $y1);
                        $to = max($y0, $y1);
                        for ($y = $from + 1; $y < $to; $y++) {
                            $result[$rows - $y][$x + $settings->getOffset()] = Color::colorize('│', $colors);
                        }
                    }
                }
            }

            $graph->addResult($result);
        }

        return $graph;
    }

    /**
     * @return Settings
     */
    public function getSettings(): Settings
    {
        if ($this->settings === null) {
            $this->settings = new Settings();
        }

        return $this->settings;
    }

    /**
     * @param Settings $settings
     *
     * @return LineGraph
     */
    public function setSettings(Settings $settings): LineGraph
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * @param Graph $graph
     */
    private function findMinMax(Graph $graph, array $allSeries): void
    {
        $max = $width = 0;
        $min = PHP_INT_MAX;
        foreach ($allSeries as $series) {
            $width = max($width, count($series['series']));

            foreach ($series['series'] as $value) {
                $min = min($min, $value);
                $max = max($max, $value);
            }
        }

        $graph->setMax($max);
        $graph->setMin($min);
        $graph->setWidth($width);
    }

    /**
     * @return LineGraph
     */
    public function clearAllSeries(): LineGraph
    {
        $this->allSeries = [];

        return $this;
    }
}
