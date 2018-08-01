<?php
declare(strict_types = 1);

namespace noximo\PHPColoredConsoleLinegraph;

/**
 * Class Graph
 * @package noximo\PHPColoredConsoleLinegraph
 */
class Graph
{
    /**
     * @var array
     */
    private $results = [];

    /**
     * @var Settings
     */
    private $settings;
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
    private $iteration = 0;
    /**
     * @var int
     */
    private $allTimeMaxHeight = 0;

    /**
     * @return int
     */
    public function getIteration(): int
    {
        return $this->iteration;
    }

    /**
     * @param int $iteration
     *
     * @return Graph
     */
    public function setIteration(int $iteration): Graph
    {
        $this->iteration = $iteration;

        return $this;
    }

    /**
     * @return Settings
     */
    public function getSettings(): Settings
    {
        return $this->settings;
    }

    /**
     * @param Settings $settings
     *
     * @return Graph
     */
    public function setSettings(Settings $settings): Graph
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * @return float
     */
    public function getMin(): float
    {
        return $this->min;
    }

    /**
     * @param float $min
     *
     * @return Graph
     */
    public function setMin(float $min): Graph
    {
        $this->min = $min;

        return $this;
    }

    /**
     * @return float
     */
    public function getMax(): float
    {
        return $this->max;
    }

    /**
     * @param float $max
     *
     * @return Graph
     */
    public function setMax(float $max): Graph
    {
        $this->max = $max;

        return $this;
    }

    /**
     * @param float $allTimeMaxHeight
     *
     * @return Graph
     */
    public function setAlltimeMaxHeight(float $allTimeMaxHeight): Graph
    {
        $this->allTimeMaxHeight = $allTimeMaxHeight;

        return $this;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param int $width
     *
     * @return Graph
     */
    public function setWidth(int $width): Graph
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @param array $result
     */
    public function addResult(array $result): void
    {
        $this->results[] = $result;
    }

    /**
     * @return Graph
     */
    public function printAndwait(): Graph
    {
        $this->print()->wait();

        return $this;
    }

    /**
     * @return Graph
     */
    public function wait(): Graph
    {
        usleep((int) round(1000000 / $this->settings->getFps()));

        return $this;
    }

    /**
     * @return Graph
     */
    public function print(): Graph
    {
        fwrite(STDOUT, $this->__toString());

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $return = '';
        foreach ($this->merge() as $row) {
            foreach ($row as $cell) {
                $return .= $cell;
            }
            $return .= PHP_EOL;
        }

        return $return;
    }

    /**
     * @return array
     */
    private function merge(): array
    {
        $merged = [];
        foreach ($this->results as $result) {
            foreach ($result as $x => $row) {
                foreach ($row as $y => $cell) {
                    if ($this->shouldBeMerged($merged, $x, $y, $cell)) {
                        $merged[$x][$y] = $cell;
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

    /**
     * @param $value
     * @param $cell
     *
     * @return bool
     */
    private function shouldBeMerged($merged, $x, $y, $cell): bool
    {
        return !isset($merged[$x][$y]) || ($cell !== ' ' && strpos($merged[$x][$y], 'm') === false);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $return = [];
        foreach ($this->merge() as $row) {
            foreach ($row as $cell) {
                $return [] = $cell;
            }
        }

        return $return;
    }

    /**
     * @param bool $quick
     *
     * @return Graph
     */
    public function clearScreen(bool $quick = true): Graph
    {
        if ($quick) {
            fwrite(STDOUT, "\033[0;0f"); //MoveCursor
        } else {
            fwrite(STDOUT, chr(27) . chr(91) . 'H' . chr(27) . chr(91) . 'J');   //^[H^[J
        }

        return $this;
    }
}
