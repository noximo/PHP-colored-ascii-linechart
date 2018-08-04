<?php
declare(strict_types = 1);

use noximo\PHPColoredConsoleLinegraph\Color;
use noximo\PHPColoredConsoleLinegraph\ColorException;
use noximo\PHPColoredConsoleLinegraph\LineGraph;

require_once dirname(__DIR__) . '/src/LineGraph.php';
require_once dirname(__DIR__) . '/src/Graph.php';
require_once dirname(__DIR__) . '/src/ColorException.php';
require_once dirname(__DIR__) . '/src/Color.php';
require_once dirname(__DIR__) . '/src/Settings.php';
try {
    $lineGraph = new LineGraph();

    $lineA = [];
    for ($i = 0; $i < 120; $i++) {
        $lineA[] = 15 * sin($i * ((pi() * 4) / 120));
    }

    $lineA = [1, 0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, null, 10, null, 12, 11, 10, 9, 8, 7, 8, 9, 9];

    $lineGraph->addSeries($lineA, [Color::GREEN, Color::BOLD]);
    $lineGraph->addPoint(5, 5, [Color::RED, Color::BOLD]);
    $lineGraph->addPoint(10, 4, [Color::GREEN, Color::BOLD]);
    $lineGraph->addPoint(8, 10, [Color::BLUE, Color::BOLD]);
    $lineGraph->addPoint(2, 1, [Color::CYAN, Color::BOLD]);

    $lineGraph->graph()->clearScreen()->print();
} catch (ReflectionException|ColorException|Exception $e) {
}
