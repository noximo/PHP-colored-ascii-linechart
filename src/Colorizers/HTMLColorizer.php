<?php
declare(strict_types = 1);

namespace noximo\PHPColoredAsciiLinechart\Colorizers;

/**
 * Class HTMLColorizer
 * @package noximo\PHPColoredAsciiLinechart\Colorizers
 */
class HTMLColorizer implements IColorizer
{

    /**
     * @param string $text
     * @param array|null $styles
     *
     * @return string
     */
    public function colorize(string $text, ?array $styles = null): string
    {
        if ($styles === null) {
            return $text;
        }

        return "<span style='" . implode(';', str_replace(' ', '', $styles)) . "'>" . $text . '</span>';
    }

    /**
     * @return string
     */
    public function getEOL(): string
    {
        return '<br>';
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function processFinalText(string $text): string
    {
        $div = "<div style='font-family:monospace,monospace; font-size:1em;'>";
        $text = str_replace([' ', 'span&nbsp;'], ['&nbsp;', 'span '], $text);
        $endDiv = '</div>';

        return $div . $text . $endDiv;
    }
}
