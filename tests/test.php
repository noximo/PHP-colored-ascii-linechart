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
try {
    $settings = new Settings();
    $settings->setFps(200)->setHeight(30);


    $lineGraph = new LineGraph();
    $lineGraph->setSettings($settings);
    for ($i = 0; $i < 120; $i++) {
        $lineA[$i] = ($lineA[$i - 1] ?? 1) + random_int(-2, 2);
        $lineB[$i] = ($lineB[$i - 1] ?? 1) + random_int(-2, 2);
        $lineC[$i] = ($lineC[$i - 1] ?? 1) + random_int(-2, 2);
        $lineD[$i] = ($lineD[$i - 1] ?? 1) + random_int(-2, 2);
    }
    for ($y = 0; $y < 1500; $y++) {
        array_shift($lineA);
        $lineA[] = end($lineA) + random_int(-2, 2);

        array_shift($lineB);
        $lineB[] = end($lineB) + random_int(-2, 2);
        array_shift($lineC);
        $lineC[] = end($lineC) + random_int(-2, 2);
        array_shift($lineD);
        $lineD[] = end($lineD) + random_int(-2, 2);

        $lineGraph->addSeries($lineA, [Color::GREEN]);
        $lineGraph->addSeries($lineB, [Color::RED]);
        $lineGraph->addSeries($lineC, [Color::BLACK, Color::BOLD]);
        $lineGraph->addSeries($lineD, [Color::WHITE]);

        $lineGraph->graph()->clearScreen()->print()->wait();
        $lineGraph->clearAllSeries();
    }
} catch (ReflectionException $e) {
} catch (ColorException $e) {
} catch (Exception $e) {
}
