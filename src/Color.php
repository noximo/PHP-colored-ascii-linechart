<?php
declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: TP
 * Date: 22.07.2018
 * Time: 21:35
 */

namespace noximo\PHPColoredConsoleLinegraph;

use ReflectionClass;

/**
 * Class Colorize
 * @package noximo\PHPColoredConsoleLinegraph
 */
class Color
{
    public const BOLD = 1;
    public const DARK = 2;
    public const ITALIC = 3;
    public const UNDERLINE = 4;
    public const BLINK = 5;
    public const REVERSE = 7;
    public const CONCEALED = 8;

    public const BLACK = 30;
    public const RED = 31;
    public const GREEN = 32;
    public const YELLOW = 33;
    public const BLUE = 34;
    public const MAGENTA = 35;
    public const CYAN = 36;
    public const LIGHT_GRAY = 37;
    public const DARK_GRAY = 90;
    public const LIGHT_RED = 91;
    public const LIGHT_GREEN = 92;
    public const LIGHT_YELLOW = 93;
    public const LIGHT_BLUE = 94;
    public const LIGHT_MAGENTA = 95;
    public const LIGHT_CYAN = 96;
    public const WHITE = 97;
    public const BACKGROUND_DEFAULT = 49;
    public const BACKGROUND_BLACK = 40;
    public const BACKGROUND_RED = 41;
    public const BACKGROUND_GREEN = 42;
    public const BACKGROUND_YELLOW = 43;
    public const BACKGROUND_BLUE = 44;
    public const BACKGROUND_MAGENTA = 45;
    public const BACKGROUND_CYAN = 46;
    public const BACKGROUND_LIGHT_GRAY = 47;
    public const BACKGROUND_DARK_GRAY = 100;
    public const BACKGROUND_LIGHT_RED = 101;
    public const BACKGROUND_LIGHT_GREEN = 102;
    public const BACKGROUND_LIGHT_YELLOW = 103;
    public const BACKGROUND_LIGHT_BLUE = 104;
    public const BACKGROUND_LIGHT_MAGENTA = 105;
    public const BACKGROUND_LIGHT_CYAN = 106;
    public const BACKGROUND_WHITE = 107;

    private static $constants = [];

    /**
     * @param string $text
     * @param int ...$colors
     *
     * @return string
     * @throws ColorException
     * @throws \ReflectionException
     */
    public static function colorize(string $text, array $colors): string
    {
        foreach ($colors as $color) {
            if (!self::colorExists($color)) {
                throw new ColorException('Unknown color ' . $color . ', use constans of class Color');
            }
        }

        $result = \chr(27) . '[0m' . \chr(27) . '[' . implode(';', $colors) . 'm' . $text . \chr(27) . '[0m';
        return $result;
    }

    /**
     * @param $color
     *
     * @return bool
     * @throws \ReflectionException
     */
    public static function colorExists(int $color): bool
    {
        if (empty(self::$constants)) {
            $oClass = new ReflectionClass(__CLASS__);
            self::$constants = $oClass->getConstants();
        }

        return \in_array($color, self::$constants, true);
    }
}
