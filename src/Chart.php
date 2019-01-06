<?php

declare(strict_types=1);

namespace noximo\PHPColoredAsciiLinechart;

/**
 * Class Graph
 * @package noximo\PHPColoredConsoleLinegraph
 */
final class Chart
{
    /**
     * @var array
     */
    private $results = [];

    /**
     * @var float
     */
    private $min;

    /**
     * @var float
     */
    private $max;

    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $allTimeMaxHeight = 0;

    /**
     * @var int
     */
    private $longestText = 0;

    /**
     * @var Settings
     */
    private $settings;

    /**
     * @var Linechart
     */
    private $chart;

    public function __construct(Linechart $chart)
    {
        $this->chart = $chart;
    }

    public function __toString()
    {
        return $this->prepareChart() . $this->prepareText();
    }
    
    public function getSettings(): Settings
    {
        return $this->settings;
    }
    
    public function setSettings(Settings $settings): self
    {
        $this->settings = $settings;

        return $this;
    }
    
    public function getResults(): array
    {
        return $this->results;
    }
    
    public function getMin(): float
    {
        return $this->min;
    }
    
    public function setMin(float $min): self
    {
        $this->min = $min;

        return $this;
    }
    
    public function getMax(): float
    {
        return $this->max;
    }
    
    public function setMax(float $max): self
    {
        $this->max = $max;

        return $this;
    }
    
    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }
    
    public function addResult(array $result): void
    {
        $this->results[] = $result;
    }
    
    public function printAndwait(): self
    {
        $this->print()->wait();

        return $this;
    }
    
    public function wait(): self
    {
        usleep((int) round(1000000 / $this->settings->getFps()));

        return $this;
    }
    
    public function print(): self
    {
        $this->output($this->prepareChart());
        $this->output($this->prepareText());

        return $this;
    }
    
    public function toArray(): array
    {
        $return = [];
        foreach ($this->merge() as $row) {
            foreach ($row as $cell) {
                $return[] = $cell;
            }
        }

        return $return;
    }
    
    public function clearScreen(?bool $useAlternativeMethod = null): self
    {
        if ($useAlternativeMethod) {
            $output = \chr(27) . \chr(91) . 'H' . \chr(27) . \chr(91) . 'J';
        } else {
            $output = "\033[0;0f";
        }

        $this->output($output);

        return $this;
    }
    
    public function setAlltimeMaxHeight(int $allTimeMaxHeight): self
    {
        $this->allTimeMaxHeight = $allTimeMaxHeight;

        return $this;
    }

    public function printText(): self
    {
        $text = $this->prepareText();

        $this->output($text);

        return $this;
    }
    
    private function prepareChart(): string
    {
        $return = '';
        foreach ($this->merge() as $row) {
            foreach ($row as $cell) {
                $return .= $cell;
            }
            $return .= $this->settings->getColorizer()->getEOL();
        }

        return $this->settings->getColorizer()->processFinalText($return);
    }

    private function prepareText(): string
    {
        $return = '';
        foreach ($this->chart->getText() as $row) {
            $line = $this->settings->getColorizer()->colorize($row[Linechart::VALUE], $row[Linechart::COLORS]);
            $lineLength = strlen($line);
            $this->longestText = $lineLength > $this->longestText ? $lineLength : $this->longestText;
            $line = str_pad($line, $this->longestText, ' ');
            $return .= $line . $this->settings->getColorizer()->getEOL();
        }

        $return = $this->settings->getColorizer()->processFinalText($return);

        $this->chart->clearText();

        return $return;
    }
    
    private function output(string $output): void
    {
        $fopen = fopen('php://stdout', 'wb');
        if (\is_resource($fopen)) {
            fwrite($fopen, $output);
        }
    }
    
    private function merge(): array
    {
        $x = 0;
        $merged = [];
        foreach ($this->results as $result) {
            foreach ($result as $x => $row) {
                foreach ($row as $y => $cell) {
                    if ($this->shouldBeMerged($merged, (string) $x, (string) $y, (string) $cell)) {
                        $merged[$x][$y] = (string) $cell;
                    }
                }
            }
        }

        if ($x < $this->allTimeMaxHeight) {
            $width = $this->width + \strlen($this->settings->getPadding());
            for ($i = 0, $iMax = $this->allTimeMaxHeight - $x; $i < $iMax; $i++) {
                $merged[] = array_fill(0, $width, ' ');
            }
        }

        return $merged;
    }
    
    private function shouldBeMerged(array $merged, string $x, string $y, string $cell): bool
    {
        return !isset($merged[$x][$y]) || ($cell !== ' ' && strpos($merged[$x][$y], 'm') === false);
    }
}
