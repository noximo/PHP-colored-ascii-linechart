<?php
declare(strict_types = 1);

namespace noximo\PHPColoredAsciiLinechart\Colorizers;

/**
 * Interface IColor
 * @package noximo\PHPColoredConsoleLinegraph\Colorizers
 */
interface IColorizer
{
    /**
     * @param string $text
     * @param array|null $colors
     *
     * @return string
     */
    public function colorize(string $text, ?array $colors = null): string;

    /**
     * @return string
     */
    public function getEOL(): string;

    /**
     * @param string $text
     *
     * @return string
     */
    public function processFinalText(string $text): string;
}
