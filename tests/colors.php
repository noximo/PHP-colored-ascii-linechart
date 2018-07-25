<?php
declare(strict_types = 1);

use noximo\PHPColoredConsoleLinegraph\Color;

require_once dirname(__DIR__) . '/src/Color.php';
require_once dirname(__DIR__) . '/src/ColorException.php';

try {
    print Color::colorize("text", Color::BACKGROUND_WHITE, Color::BLACK, Color::BOLD) . PHP_EOL;
    print Color::colorize("text", Color::REVERSE) . PHP_EOL;
    print Color::colorize("text", Color::RED) . PHP_EOL;
} catch (ReflectionException | \noximo\PHPColoredConsoleLinegraph\ColorException $e) {
    echo $e->getMessage();
}