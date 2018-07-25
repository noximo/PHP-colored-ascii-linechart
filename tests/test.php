<?php
declare(strict_types = 1);

use noximo\PHPColoredConsoleLinegraph\Color;
use noximo\PHPColoredConsoleLinegraph\ColorException;
use noximo\PHPColoredConsoleLinegraph\LineGraph;
use noximo\PHPColoredConsoleLinegraph\Settings;

require_once dirname(__DIR__) . '/src/LineGraph.php';
require_once dirname(__DIR__) . '/src/Graph.php';
require_once dirname(__DIR__) . '/src/ColorException.php';
require_once dirname(__DIR__) . '/src/Color.php';
require_once dirname(__DIR__) . '/src/Settings.php';

$settings = new Settings();

$lineGraph = new LineGraph();

$lineGraph->addSeries([1, 4, 55, 2, 4, 1, 5, 2, 3, 4, 5], [Color::RED]);
$lineGraph->addSeries([5, 4, 32, 2, 1], [Color::GREEN]);

$settings->setPadding(4)->setHeight(10);
$lineGraph->setSettings($settings);
try {
    $lineGraph->graph()->print()->wait();
} catch (ReflectionException $e) {
} catch (ColorException $e) {
}
