<?php

declare(strict_types=1);

namespace noximo\PHPColoredAsciiLinechart;

use noximo\PHPColoredAsciiLinechart\Colorizers\ColorizerInterface;
use function strlen;
use function in_array;

/**
 * Class LineGraph
 * @package noximo\PHPColoredConsoleLinegraph
 */
final class Linechart
{
    /** @var string */
    public const CROSS = 'cross';

    /** @var string */
    public const POINT = 'point';

    /** @var string */
    public const DASHED_LINE = 'dashedLine';

    /** @var string */
    public const POINT_X = 'x';

    /** @var string */
    public const POINT_Y = 'y';

    /** @var string */
    public const VALUE = 'value';

    /** @var string */
    public const COLORS = 'colors';

    /** @var string */
    public const FULL_LINE = 'fullLIne';

    /** @var string */
    public const MARKERS = 'markers';

    /** @var string */
    public const COLORS_DOWN = 'colorsDown';

    /** @var string */
    public const SPREADS = 'spreads';

    /**
     * @var array = [
     * [['markers' => [1,2,3.45], SELF::COLORS => [1,2,3]]];
     * ]
     */
    private $allmarkers = [];

    /** @var int */
    private $width;

    /**
     * @var array|null
     */
    private $currentColors;

    /**
     * @var float
     */
    private $range = 0.0;

    /**
     * @var float
     */
    private $ratio = 0.0;

    /**
     * @var float
     */
    private $min2 = 0.0;

    /**
     * @var float
     */
    private $max2 = 0.0;

    /**
     * @var int
     */
    private $rows = 1;

    /**
     * @var int
     */
    private $offset = 0;

    /**
     * @var float|null
     */
    private $adjuster;

    /**
     * @var array
     */
    private $text = [];

    /**
     * @var Settings|null
     */
    private $settings;

    /**
     * @var ColorizerInterface
     */
    private $colorizer;

    /**
     * @param int $x alias x coordinate
     * @param float $y alias y coordinate
     * @param array|null $colors
     * @param string|null $appearance
     * @return Linechart
     */
    public function addPoint(int $x, float $y, ?array $colors = null, ?string $appearance = null): self
    {
        $markers = [];
        $markers[0] = $y;
        $markers[$x] = $y;
        if (!in_array($appearance, [self::CROSS, self::POINT], true)) {
            $appearance = self::POINT;
        }
        $this->addMarkerData($markers, $colors, null, $appearance);

        return $this;
    }

    /**
     * @param array $markers
     * @param array|null $colors
     * @param array|null $colorsDown
     * @return Linechart
     */
    public function addMarkers(array $markers, ?array $colors = null, ?array $colorsDown = null): self
    {
        $this->addMarkerData($markers, $colors, $colorsDown);

        return $this;
    }

    public function chart(): Chart
    {
        $graph = new Chart($this);
        $graph->setSettings($this->getSettings());
        $this->prepareData();

        foreach ($this->allmarkers as $markersData) {
            $result = $this->getResultFromMarkersData($markersData);

            $this->currentColors = null;
            $graph->addResult($result);
        }

        $graph->setWidth($this->width);

        return $graph;
    }

    public function getSettings(): Settings
    {
        if ($this->settings === null) {
            $this->settings = new Settings();
        }

        return $this->settings;
    }

    public function setSettings(Settings $settings): self
    {
        $this->settings = $settings;

        return $this;
    }

    public function clearAllMarkers(): self
    {
        $this->allmarkers = [];

        return $this;
    }

    /**
     * @param string $value
     * @param string[] $color
     */
    public function addText(string $value, array $color): void
    {
        $this->text[] = [
            self::VALUE => $value,
            self::COLORS => $color,
        ];
    }

    /**
     * @param array $values
     * @param float $mainValue
     * @param array $colors
     */
    public function addSpread(array $values, float $mainValue, array $colors): void
    {
        foreach ($values as $value) {
            $colors = $colors ?? [];
            $appearance = $value === 1 ? self::FULL_LINE : self::DASHED_LINE;
            $this->addLine($value * $mainValue, $colors ?? [], $appearance);
        }
    }

    /**
     * @param float $value alias y coordinate
     * @param array|null $colors
     * @param string|null $appearance
     * @return Linechart
     */
    public function addLine(float $value, ?array $colors = null, ?string $appearance = null): self
    {
        $markers = [];
        $markers[0] = $value;
        if (!in_array($appearance, [self::DASHED_LINE, self::FULL_LINE], true)) {
            $appearance = self::DASHED_LINE;
        }
        $this->addMarkerData($markers, $colors, null, $appearance);

        return $this;
    }

    public function getText(): array
    {
        return $this->text;
    }

    public function clearText(): void
    {
        $this->text = [];
    }

    /**
     * @param array $markers
     * @param array|null $colors
     * @param array|null $colorsDown
     * @param string|null $point
     * @return Linechart
     */
    private function addMarkerData(array $markers, ?array $colors = null, ?array $colorsDown = null, ?string $point = null): self
    {
        $markersData = [
            self::MARKERS => $this->normalizeData($markers),
            self::COLORS => $colors ?? [],
            self::COLORS_DOWN => $colorsDown ?? $colors ?? [],
            self::POINT => $point,
        ];

        $this->allmarkers[] = $markersData;

        return $this;
    }

    private function prepareData(): void
    {
        [$min, $max, $width] = $this->findMinMax($this->allmarkers);

        $this->adjuster = $this->findAdjuster($min, $max);
        $max = $this->adjust($max);
        $min = $this->adjust($min);

        $this->range = max(1, abs($max - $min));

        $height = (int) ($this->getSettings()->getHeight() ?? $this->range);
        $this->ratio = $height / $this->range;

        $this->min2 = $min * $this->ratio;
        $this->max2 = $max * $this->ratio;

        $this->rows = (int) max(0, abs(round($this->max2 - $this->min2)));

        $this->offset = $this->getSettings()->getOffset();

        $this->width = $width + $this->offset;
    }

    private function getResultFromMarkersData(array $markersData): array
    {
        $markersData[self::MARKERS] = $this->adjustMarkerValues($markersData[self::MARKERS]);
        $this->currentColors = $this->currentColors ?? $markersData[self::COLORS];

        $result = $this->processBorder($this->prepareResult(), $markersData);

        $isPoint = in_array($markersData[self::POINT], [self::CROSS, self::POINT], true);
        $isLine = in_array($markersData[self::POINT], [self::DASHED_LINE, self::FULL_LINE], true);

        foreach ($markersData[self::MARKERS] as $x => $value) {
            $y0 = (int) (round($value * $this->ratio) - $this->min2);

            if ($this->isPresent($markersData[self::MARKERS], $x + 1)) {
                $result = $this->processLinearGraph($result, $markersData, $x, $y0);
            } elseif ($x !== 0 && $isPoint) {
                $result = $this->processPoint($result, $markersData, $y0, $x);
            } elseif ($x === 0 && $isLine) {
                $result = $this->processLine($result, $y0, $markersData[self::POINT]);
            }
        }

        return $result;
    }

    private function normalizeData(array $markers): array
    {
        $markers = array_filter($markers, '\is_int', ARRAY_FILTER_USE_KEY);
        ksort($markers);

        reset($markers);
        $firstKey = key($markers);

        $keys = [];
        foreach (array_keys($markers) as $key) {
            $keys[] = $key - $firstKey;
        }

        $combined = array_combine($keys, $markers);

        if ($combined === false) {
            return [];
        }

        return $combined;
    }

    private function findMinMax(array $allmarkers): array
    {
        $width = 0;
        $min = PHP_INT_MAX;
        $max = -PHP_INT_MAX;
        foreach ($allmarkers as $markers) {
            end($markers[self::MARKERS]);
            $width = (int) max($width, key($markers[self::MARKERS]));

            /** @var int[][] $markers */
            foreach ($markers[self::MARKERS] as $value) {
                if ($value !== null && $value !== false) {
                    $min = min($min, $value);
                    $max = max($max, $value);
                }
            }
        }

        return [$min, $max, $width];
    }

    private function findAdjuster(float $min, float $max): ?float
    {
        $adjuster = null;
        $realMin = $max - $min;

        if ($realMin < 1 && $realMin > 0) {
            $adjuster = 1 / $realMin;
        }

        return $adjuster;
    }

    private function adjust(float $number): float
    {
        if ($this->adjuster !== null) {
            $number *= $this->adjuster;
        }

        return $number;
    }

    private function adjustMarkerValues(array $markers): array
    {
        if ($this->adjuster === null) {
            return $markers;
        }

        return array_map(function ($value) {
            return $this->adjuster !== null ? $value * $this->adjuster : $value;
        }, $markers);
    }

    private function processBorder(array $result, array $markersData): array
    {
        $format = $this->getSettings()->getFormat();
        $y0 = (int) (round($markersData[self::MARKERS][0] * $this->ratio) - $this->min2);
        $y = (int) floor($this->min2);
        $yMax = (int) ceil($this->max2);

        for (; $y <= $yMax; ++$y) {
            $rows = $this->rows === 0 ? 1 : $this->rows;
            $rawLabel = $this->max2 / $this->ratio - ($y - $this->min2) * $this->range / $rows;
            $rawLabel = $this->deadjust($rawLabel);
            $label = $format($rawLabel, $this->getSettings());

            $border = '┤';
            if ($y - $this->min2 === (float) ($rows - $y0)) {
                $label = $this->colorize($label, $this->currentColors);
                $border = $this->colorize('┼', $this->currentColors);
            }

            $result[$y - $this->min2][max($this->offset - strlen($label), 0)] = $label;
            $result[$y - $this->min2][$this->offset - 1] = $border;
        }

        return $result;
    }

    private function prepareResult(): array
    {
        $result = [];

        /** @noinspection ForeachInvariantsInspection */
        for ($i = 0; $i <= $this->rows; $i++) {
            $result[$i] = array_fill(0, $this->width, ' ');
        }

        return $result;
    }

    private function isPresent(array $markers, int $x): bool
    {
        return isset($markers[$x]) && ($markers[$x] !== null && $markers[$x] !== false);
    }

    private function processLinearGraph(array $result, array $markersData, int $x, int $y): array
    {
        $y1 = (int) (round($markersData[self::MARKERS][$x + 1] * $this->ratio) - $this->min2);
        if ($y === $y1) {
            $result[$this->rows - $y][$x + $this->offset] = $this->colorize('─', $this->currentColors);
        } else {
            if ($y > $y1) {
                $connectA = '╰';
                $connectB = '╮';

                $this->currentColors = $markersData[self::COLORS_DOWN];
            } else {
                $connectA = '╭';
                $connectB = '╯';

                $this->currentColors = $markersData[self::COLORS];
            }
            $result[$this->rows - $y1][$x + $this->offset] = $this->colorize($connectA, $this->currentColors);
            $result[$this->rows - $y][$x + $this->offset] = $this->colorize($connectB, $this->currentColors);

            $from = min($y, $y1);
            $to = max($y, $y1);
            for ($i = $from + 1; $i < $to; $i++) {
                $result[$this->rows - $i][$x + $this->offset] = $this->colorize('│', $this->currentColors);
            }
        }

        return $result;
    }

    private function processPoint(array $result, array $markersData, int $y, int $x): array
    {
        if ($markersData[self::POINT] === self::CROSS) {
            for ($i = 0; $i <= $this->width - $this->offset - 2; $i++) {
                $result[$this->rows - $y][$i + $this->offset] = $this->colorize('╌', $this->currentColors);
            }
            for ($i = 0; $i <= $this->rows; $i++) {
                $result[$this->rows - $i][$x + $this->offset] = $this->colorize('╎', $this->currentColors);
            }
        }

        $result[$this->rows - $y][$x + $this->offset] = $this->colorize('o', $this->currentColors);

        return $result;
    }

    private function processLine(array $result, int $y, string $lineStyle): array
    {
        $line = '╌';
        if ($lineStyle === self::FULL_LINE) {
            $line = '─';
        }

        for ($i = 0; $i <= $this->width - $this->offset - 2; $i++) {
            $result[$this->rows - $y][$i + $this->offset] = $this->colorize($line, $this->currentColors);
        }

        return $result;
    }

    private function deadjust(float $number): float
    {
        if ($this->adjuster !== null) {
            $number /= $this->adjuster;
        }

        return $number;
    }

    private function colorize(string $label, ?array $currentColors = null): string
    {
        if ($this->colorizer === null) {
            $this->colorizer = $this->getSettings()->getColorizer();
        }

        return $this->colorizer->colorize($label, $currentColors);
    }
}
