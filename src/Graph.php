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
    private $time;

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
     * @return Graph
     */
    public function print(): Graph
    {
        echo $this;

        return $this;
    }

    public function wait():Graph
    {
        sleep($this->time);

        return $this;
    }
}