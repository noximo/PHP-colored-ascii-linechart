<?php
declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: TP
 * Date: 22.07.2018
 * Time: 21:37
 */

namespace noximo\PHPColoredConsoleLinegraph;

/**
 * Class LineGraph
 * @package noximo\PHPColoredConsoleLinegraph
 */
class LineGraph
{
    /**
     * @param $series
     * @param Config $config
     *
     * @return string
     */
    public function chart($series, Config $config)
    {
        $min = $series[0];
        $max = $series[0];

        $width = $count = count($series);
        for ($i = 1; $i < $width; $i++) {
            $min = min($min, $series[$i]);
            $max = max($max, $series[$i]);
        }

        $range = max(1, abs($max - $min));

        $height = $config->getHeight() ?? $range;

        $ratio = $height / $range;
        $min2 = (int) round($min * $ratio);
        $max2 = (int) round($max * $ratio);

        $rows = max(1, abs($max2 - $min2));
        $width += $config->getOffset();

        $result = [];

        for ($i = 0; $i <= $rows; $i++) {
            $result[$i] = array_fill(0, $width, ' ');
        }

        $format = $config->getFormat();
        for ($y = $min2; $y <= $max2; ++$y) { // axis + labels
            $rawLabel = $max - ($y - $min2) * $range / $rows;
            $label = $format($rawLabel, $config);

            $result[$y - $min2][max($config->getOffset() - strlen($label), 0)] = $label;
            $result[$y - $min2][$config->getOffset()-1] = ($y == 0) ? '┼' : '┤';
        }

        $y0 = round($series[0] * $ratio) - $min2;
        $result[$rows - $y0][$config->getOffset() - 1] = '┼'; // first value

        for ($x = 0; $x < $count - 1; $x++) { // plot the line
            $y0 = (int) round($series[$x + 0] * $ratio) - $min2;
            $y1 = (int) round($series[$x + 1] * $ratio) - $min2;
            if ($y0 == $y1) {
                $result[$rows - $y0][$x + $config->getOffset()] = '─';
            } else {
                $result[$rows - $y1][$x + $config->getOffset()] = ($y0 > $y1) ? '╰' : '╭';
                $result[$rows - $y0][$x + $config->getOffset()] = ($y0 > $y1) ? '╮' : '╯';
                $from = min($y0, $y1);
                $to = max($y0, $y1);
                for ($y = $from + 1; $y < $to; $y++) {
                    $result[$rows - $y][$x + $config->getOffset()] = '│';
                }
            }
        }

        $return = '';
        foreach ($result as $row) {
            foreach ($row as $cell) {
                $return .= $cell;
            }
            $return .= PHP_EOL;
        }
        print $return;
    }
}
