<?php
declare(strict_types = 1);

namespace noximo\PHPColoredAsciiLinechart\Colorizers;

/**
 * Class HTMLColorizer
 * @package noximo\PHPColoredAsciiLinechart\Colorizers
 */
class ImageColorizer implements IColorizer
{

    /**
     * @param string $text
     * @param array|null $styles
     *
     * @return string
     */
    public function colorize(string $text, ?array $styles = null): string
    {
        return $text;
    }

    /**
     * @return string
     */
    public function getEOL(): string
    {
        return PHP_EOL;
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function processFinalText(string $text): string
    {
        return $text;
    }
}
