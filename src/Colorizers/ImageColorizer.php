<?php

declare(strict_types=1);

namespace noximo\PHPColoredAsciiLinechart\Colorizers;

/**
 * Class HTMLColorizer
 * @package noximo\PHPColoredAsciiLinechart\Colorizers
 */
final class ImageColorizer implements ColorizerInterface
{
    /**
     * @param array|null $styles
     */
    public function colorize(string $text, ?array $styles = null): string
    {
        return $text;
    }

    public function getEOL(): string
    {
        return PHP_EOL;
    }

    public function processFinalText(string $text): string
    {
        return $text;
    }
}
