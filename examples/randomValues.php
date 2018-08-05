<?php
declare(strict_types = 1);

use noximo\PHPColoredAsciiLinechart\Colorizers\AsciiColorizer;
use noximo\PHPColoredAsciiLinechart\Linechart;
use noximo\PHPColoredAsciiLinechart\Settings;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
$settings = new Settings();
$settings->setFPS(120);
$lineGraph = new Linechart();
$lineGraph->setSettings($settings);

try {
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

        $lineGraph->addMarkers($lineA, [AsciiColorizer::GREEN, AsciiColorizer::BOLD]);
        $lineGraph->addMarkers($lineB, [AsciiColorizer::GREEN], [AsciiColorizer::RED]);
        $lineGraph->addMarkers($lineC, [AsciiColorizer::CYAN, AsciiColorizer::BOLD]);
        $lineGraph->addMarkers($lineD, [AsciiColorizer::WHITE, AsciiColorizer::BOLD]);

        $lineGraph->addLine(0, [AsciiColorizer::CYAN], Linechart::FULL_LINE);

        $lineGraph->chart()->clearScreen()->print()->wait();
        $lineGraph->clearAllMarkers();
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
