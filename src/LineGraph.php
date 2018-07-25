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
     * @var Graph
     */
    private $graph;

    /**
     * @var Settings
     */
    private $settings;
    /**
     * @var array $series = [
     * 'legend' => ['series' => [1,2,3.45], 'colors' => [1,2,3], 'legend' => 'legend'];
     * ]
     */
    private $series = [];
    /**
     * @var array $points = ['x' => 1, 'y' => 0.75]
     */
    private $points = [];

    /**
     * LineGraph constructor.
     */
    public function __construct()
    {
        $this->graph = new Graph();
    }

    /**
     * @param array $series
     * @param array $colors
     * @param string|null $legend
     *
     * @return LineGraph
     */
    public function addSeries(array $series, array $colors = [], string $legend = null): LineGraph
    {
        $this->series[] = ['series' => $series, 'colors' => $colors, 'legend' => $legend];

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
        $this->findMinMax();

        $min = $this->graph->getMin();
        $max = $this->graph->getMax();
        $width = $this->graph->getWidth();
        $count = $this->graph->getWidth();

        $settings = $this->getSettings();

        $range = max(1, abs($max - $min));

        $height = $settings->getHeight() ?? $range;

        $ratio = $height / $range;
        $min2 = (int) round($min * $ratio);
        $max2 = (int) round($max * $ratio);

        $rows = max(1, abs($max2 - $min2));
        $width += $settings->getOffset();

        foreach ($this->series as $seriesData) {
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
                if (!empty($series[$x]) && !empty($series[$x+1])) {
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

            $this->graph->addResult($result);
        }

        return $this->graph;
    }

    private function findMinMax(): void
    {
        $max = $width = 0;
        $min = PHP_INT_MAX;
        foreach ($this->series as $series) {
            $width = max($width, count($series['series']));

            foreach ($series['series'] as $value) {
                $min = min($min, $value);
                $max = max($max, $value);
            }
        }

        $this->graph->setMax($max);
        $this->graph->setMin($min);
        $this->graph->setWidth($width);
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
}
