<?php

declare(strict_types=1);

namespace noximo\PHPColoredAsciiLinechart\Colorizers;

/**
 * Interface IColor
 * @package noximo\PHPColoredConsoleLinegraph\Colorizers
 */
interface IColorizer
{
    /**
     * @param array|null $colors
     */
    public function colorize(string $text, ?array $colors = null): string;
    
    public function getEOL(): string;
    
    public function processFinalText(string $text): string;
}
