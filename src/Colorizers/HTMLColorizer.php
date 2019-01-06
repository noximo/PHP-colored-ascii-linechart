<?php

declare(strict_types=1);

namespace noximo\PHPColoredAsciiLinechart\Colorizers;

/**
 * Class HTMLColorizer
 * @package noximo\PHPColoredAsciiLinechart\Colorizers
 */
final class HTMLColorizer implements IColorizer
{
    /**
     * @param array|null $styles
     */
    public function colorize(string $text, ?array $styles = null): string
    {
        if ($styles === null) {
            return $text;
        }

        $cssStyles = implode(';', str_replace(' ', '', $styles));

        return "<span style='" . $cssStyles . "'>" . $text . '</span>';
    }
    
    public function getEOL(): string
    {
        return '<br>';
    }
    
    public function processFinalText(string $text): string
    {
        $div = "<div style='font-family:monospace,monospace; font-size:1em;'>";
        $text = str_replace([' ', 'span&nbsp;'], ['&nbsp;', 'span '], $text);
        $endDiv = '</div>';

        return $div . $text . $endDiv;
    }
}
