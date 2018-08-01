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
     * @var int
     */
    private $allTimeMaxHeight = 0;
    /**
     * @var array
     */
    private $currentColors;

    /**
     * @param array $series
     * @param array $colors
     * @param string|null $legend
     *
     * @return LineGraph
     */
    public function addSeries(
        array $series,
        array $colors = [],
        array $colorsDown = null,
        string $legend = null
    ): LineGraph
    {
        $seriesData = ['series' => $series, 'colors' => $colors, 'colorsDown' => $colorsDown, 'legend' => $legend];
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

        $range = (int) max(1, abs($max - $min));
        $settings->setComputedHeight($range);

        $graph->setAlltimeMaxHeight($this->allTimeMaxHeight);

        $ratio = $settings->getHeight() / $range;
        $min2 = (int) round($min * $ratio);
        $max2 = (int) round($max * $ratio);

        $rows = max(1, abs($max2 - $min2));

        $this->allTimeMaxHeight = max($this->allTimeMaxHeight, $rows);
        $offset = $settings->getOffset();
        $width += $offset;

        foreach ($allSeries as $seriesData) {
            $series = $seriesData['series'];
            $colors = $seriesData['colors'];
            $colorsDown = $seriesData['colorsDown'] ?? $colors;

            $this->currentColors = $this->currentColors ?? $colors;
            $result = [];

            /** @noinspection ForeachInvariantsInspection */
            for ($i = 0; $i <= $rows; $i++) {
                $result[$i] = array_fill(0, $width, ' ');
            }

            $format = $settings->getFormat();
            $y0 = round($series[0] * $ratio) - $min2;
            for ($y = $min2; $y <= $max2; ++$y) { // axis + labels
                $rawLabel = $max - ($y - $min2) * $range / $rows;
                $label = $format($rawLabel, $settings);

                $border = '┤';
                if ($y - $min2 == $rows - $y0) {
                    $label = Color::colorize($label, $this->currentColors);
                    $border = Color::colorize('┼', $this->currentColors);
                }

                $result[$y - $min2][max($offset - \strlen($label), 0)] = $label;
                $result[$y - $min2][$offset - 1] = $border;
            }

            for ($x = 0; $x < $count - 1; $x++) {
                if (isset($series[$x]) && isset($series[$x + 1])) {
                    $y0 = (int) round($series[$x] * $ratio) - $min2;
                    $y1 = (int) round($series[$x + 1] * $ratio) - $min2;
                    if ($y0 === $y1) {
                        $result[$rows - $y0][$x + $offset] = Color::colorize('─', $this->currentColors);
                    } else {
                        if ($y0 > $y1) {
                            $connectA = '╰';
                            $connectB = '╮';

                            $this->currentColors = $colorsDown;
                        } else {
                            $connectA = '╭';
                            $connectB = '╯';

                            $this->currentColors = $colors;
                        }
                        $result[$rows - $y1][$x + $offset] = Color::colorize($connectA, $this->currentColors);
                        $result[$rows - $y0][$x + $offset] = Color::colorize($connectB, $this->currentColors);

                        $from = min($y0, $y1);
                        $to = max($y0, $y1);
                        for ($y = $from + 1; $y < $to; $y++) {
                            $result[$rows - $y][$x + $offset] = Color::colorize('│', $this->currentColors);
                        }
                    }
                }
            }

            $this->currentColors = null;
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
     * @param array $allSeries
     */
    private function findMinMax(Graph $graph, array $allSeries): void
    {
        $width = 0;
        $min = PHP_INT_MAX;
        $max = -PHP_INT_MAX;
        foreach ($allSeries as $series) {
            $width = max($width, \count($series['series']));

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
