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
    const CROSS = 'cross';
    const POINT = 'point';
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
     * @var int
     */
    private $allTimeMaxHeight = 0;
    /**
     * @var array
     */
    private $currentColors;

    private $width;
    /**
     * @var int
     */
    private $count;
    /**
     * @var int
     */
    private $range;
    /**
     * @var float
     */
    private $ratio;
    /**
     * @var int
     */
    private $min2;
    /**
     * @var int
     */
    private $max2;
    /**
     * @var int
     */
    private $rows;
    /**
     * @var int
     */
    private $offset;

    /**
     * @param int $time alias x coordinate
     * @param float $value alias y coordinate
     * @param array $colors
     * @param string|null $legend
     * @param bool $cross
     *
     * @return LineGraph
     */
    public function addPoint(
        int $time,
        float $value,
        array $colors = [],
        string $legend = null,
        $cross = null
    ): LineGraph
    {
        $series[0] = $value;
        $series[$time] = $value;
        $point = $cross ? self::POINT : self::CROSS;
        $this->addSeries($series, $colors, null, $legend, $point);

        return $this;
    }

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
        string $legend = null,
        string $point = null
    ): LineGraph
    {
        $seriesData = [
            'series' => $series,
            'colors' => $colors,
            'colorsDown' => $colorsDown ?? $colors,
            'legend' => $legend,
            'point' => $point,
        ];

        if ($legend) {
            $this->allSeries[$legend] = $seriesData;
        } else {
            $this->allSeries[] = $seriesData;
        }

        return $this;
    }

    /**
     * @return Graph
     * @throws ColorException
     * @throws ReflectionException
     */
    public function graph(): Graph
    {
        $graph = $this->prepareData();

        foreach ($this->allSeries as $seriesData) {
            $this->currentColors = $this->currentColors ?? $seriesData['colors'];
            $result = $this->prepareResult();

            $result = $this->processBorder($result, $seriesData, $graph);

            for ($x = 0; $x < $this->count - 1; $x++) {
                if ($this->isPresent($seriesData['series'], $x)) {
                    $y0 = (int) round($seriesData['series'][$x] * $this->ratio) - $this->min2;
                    if ($this->isPresent($seriesData['series'], $x + 1)) {
                        $result = $this->processLinearGraph($result, $seriesData, $x, $y0);
                    } elseif ($x !== 0 && $seriesData['point'] !== null) {
                        $result = $this->processPoint($result, $seriesData, $y0, $x);
                    }
                }
            }

            $this->currentColors = null;
            $graph->addResult($result);
        }

        return $graph;
    }

    /**
     * @return Graph
     */
    private function prepareData(): Graph
    {
        $graph = new Graph();
        $graph->setSettings($this->getSettings());

        $this->findMinMax($graph, $this->allSeries);

        $this->width = $graph->getWidth();
        $this->count = $graph->getWidth();

        $this->range = (int) max(1, abs($graph->getMax() - $graph->getMin()));
        $this->getSettings()->setComputedHeight($this->range);

        $graph->setAlltimeMaxHeight($this->allTimeMaxHeight);

        $this->ratio = $this->getSettings()->getHeight() / $this->range;
        $this->min2 = (int) round($graph->getMin() * $this->ratio);
        $this->max2 = (int) round($graph->getMax() * $this->ratio);

        $this->rows = max(1, abs($this->max2 - $this->min2));

        $this->allTimeMaxHeight = max($this->allTimeMaxHeight, $this->rows);
        $this->offset = $this->getSettings()->getOffset();
        $this->width += $this->offset;

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
     * @return array
     */
    private function prepareResult(): array
    {
        $result = [];

        /** @noinspection ForeachInvariantsInspection */
        for ($i = 0; $i <= $this->rows; $i++) {
            $result[$i] = array_fill(0, $this->width, ' ');
        }

        return $result;
    }

    /**
     * @param array $result
     * @param array $seriesData
     * @param Graph $graph
     *
     * @return array
     * @throws ColorException
     * @throws ReflectionException
     */
    private function processBorder(array $result, array $seriesData, Graph $graph): array
    {
        $format = $this->getSettings()->getFormat();
        $y0 = round($seriesData['series'][0] * $this->ratio) - $this->min2;
        for ($y = $this->min2; $y <= $this->max2; ++$y) { // axis + labels
            $rawLabel = $graph->getMax() - ($y - $this->min2) * $this->range / $this->rows;
            $label = $format($rawLabel, $this->getSettings());

            $border = '┤';
            if ($y - $this->min2 == $this->rows - $y0) {
                $label = Color::colorize($label, $this->currentColors);
                $border = Color::colorize('┼', $this->currentColors);
            }

            $result[$y - $this->min2][max($this->offset - \strlen($label), 0)] = $label;
            $result[$y - $this->min2][$this->offset - 1] = $border;
        }

        return $result;
    }

    /**
     * @param array $series
     * @param int $x
     *
     * @return bool
     */
    private function isPresent(array $series, int $x): bool
    {
        return isset($series[$x]) && ($series[$x] !== null || $series[$x] !== false);
    }

    /**
     * @param $result
     * @param $seriesData
     * @param $x
     * @param $y
     *
     * @return array
     * @throws ColorException
     * @throws ReflectionException
     */
    private function processLinearGraph(array $result, array $seriesData, int $x, int $y): array
    {
        $y1 = (int) round($seriesData['series'][$x + 1] * $this->ratio) - $this->min2;
        if ($y === $y1) {
            $result[$this->rows - $y][$x + $this->offset] = Color::colorize('─', $this->currentColors);
        } else {
            if ($y > $y1) {
                $connectA = '╰';
                $connectB = '╮';

                $this->currentColors = $seriesData['colorsDown'];
            } else {
                $connectA = '╭';
                $connectB = '╯';

                $this->currentColors = $seriesData['colors'];
            }
            $result[$this->rows - $y1][$x + $this->offset] = Color::colorize($connectA, $this->currentColors);
            $result[$this->rows - $y][$x + $this->offset] = Color::colorize($connectB, $this->currentColors);

            $from = min($y, $y1);
            $to = max($y, $y1);
            for ($i = $from + 1; $i < $to; $i++) {
                $result[$this->rows - $i][$x + $this->offset] = Color::colorize('│', $this->currentColors);
            }
        }

        return $result;
    }

    /**
     * @param $result
     * @param $seriesData
     * @param $y0
     * @param $x
     *
     * @return array
     * @throws ColorException
     * @throws ReflectionException
     */
    private function processPoint($result, $seriesData, $y0, $x): array
    {
        if ($seriesData['point'] === self::CROSS) {
            for ($i = 0; $i <= $this->width - $this->offset - 2; $i++) {
                $result[$this->rows - $y0][$i + $this->offset] = Color::colorize('╌', $this->currentColors);
            }
            for ($i = 0; $i <= $this->rows; $i++) {
                $result[$this->rows - $i][$x + $this->offset] = Color::colorize('╎', $this->currentColors);
            }
        }

        $result[$this->rows - $y0][$x + $this->offset] = Color::colorize("o", $this->currentColors);

        return $result;
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
