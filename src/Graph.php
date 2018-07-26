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
        $this->iteration++;

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
                    if (!isset($merged[$x][$y]) || ($cell !== ' ' && strpos($merged[$x][$y], "┼") === false)) {
                        $merged[$x][$y] = $cell;
                    }
                }
            }
        }

        return $merged;
    }

    /**
     * @return string
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
     * @return Graph
     */
    public function clearScreen(): Graph
    {
        var_dump($this->iteration);
        if ($this->iteration == 0) {
            fwrite(STDOUT, "\033[2J");
        }

        fwrite(STDOUT, "\033[0;0f"); //MoveCursor

        return $this;
    }

}