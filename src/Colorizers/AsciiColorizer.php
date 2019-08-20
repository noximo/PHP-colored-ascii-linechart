<?php

declare(strict_types=1);

namespace noximo\PHPColoredAsciiLinechart\Colorizers;

use ReflectionClass;
use ReflectionException;
use function chr;
use function in_array;

/**
 * Class Colorize
 * @package noximo\PHPColoredConsoleLinegraph
 */
final class AsciiColorizer implements ColorizerInterface
{
    /** @var int */
    public const BOLD = 1;

    /** @var int */
    public const DARK = 2;

    /** @var int */
    public const ITALIC = 3;

    /** @var int */
    public const UNDERLINE = 4;

    /** @var int */
    public const BLINK = 5;

    /** @var int */
    public const REVERSE = 7;

    /** @var int */
    public const CONCEALED = 8;

    /** @var int */
    public const BLACK = 30;

    /** @var int */
    public const RED = 31;

    /** @var int */
    public const GREEN = 32;

    /** @var int */
    public const YELLOW = 33;

    /** @var int */
    public const BLUE = 34;

    /** @var int */
    public const MAGENTA = 35;

    /** @var int */
    public const CYAN = 36;

    /** @var int */
    public const LIGHT_GRAY = 37;

    /** @var int */
    public const DARK_GRAY = 90;

    /** @var int */
    public const LIGHT_RED = 91;

    /** @var int */
    public const LIGHT_GREEN = 92;

    /** @var int */
    public const LIGHT_YELLOW = 93;

    /** @var int */
    public const LIGHT_BLUE = 94;

    /** @var int */
    public const LIGHT_MAGENTA = 95;

    /** @var int */
    public const LIGHT_CYAN = 96;

    /** @var int */
    public const WHITE = 97;

    /** @var int */
    public const BACKGROUND_DEFAULT = 49;

    /** @var int */
    public const BACKGROUND_BLACK = 40;

    /** @var int */
    public const BACKGROUND_RED = 41;

    /** @var int */
    public const BACKGROUND_GREEN = 42;

    /** @var int */
    public const BACKGROUND_YELLOW = 43;

    /** @var int */
    public const BACKGROUND_BLUE = 44;

    /** @var int */
    public const BACKGROUND_MAGENTA = 45;

    /** @var int */
    public const BACKGROUND_CYAN = 46;

    /** @var int */
    public const BACKGROUND_LIGHT_GRAY = 47;

    /** @var int */
    public const BACKGROUND_DARK_GRAY = 100;

    /** @var int */
    public const BACKGROUND_LIGHT_RED = 101;

    /** @var int */
    public const BACKGROUND_LIGHT_GREEN = 102;

    /** @var int */
    public const BACKGROUND_LIGHT_YELLOW = 103;

    /** @var int */
    public const BACKGROUND_LIGHT_BLUE = 104;

    /** @var int */
    public const BACKGROUND_LIGHT_MAGENTA = 105;

    /** @var int */
    public const BACKGROUND_LIGHT_CYAN = 106;

    /** @var int */
    public const BACKGROUND_WHITE = 107;

    /** @var array */
    private static $constants = [];

    /**
     * @param array|null $colors
     *
     * @return string
     * @throws ColorException
     * @throws ReflectionException
     */
    public function colorize(string $text, ?array $colors = null): string
    {
        if ($colors === null) {
            return $text;
        }

        foreach ($colors as $color) {
            if (!$this->colorExists((int) $color)) {
                throw new ColorException('Unknown color ' . $color . ', use constans of class Color');
            }
        }

        $chr27 = chr(27);

        return sprintf('%s[0m%s[%sm%s%s[0m', $chr27, $chr27, implode(';', $colors), $text, $chr27);
    }

    /**
     * @throws ReflectionException
     */
    public function colorExists(int $color): bool
    {
        if (count(self::$constants) === 0) {
            $oClass = new ReflectionClass(self::class);
            self::$constants = $oClass->getConstants();
        }

        return in_array($color, self::$constants, true);
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
