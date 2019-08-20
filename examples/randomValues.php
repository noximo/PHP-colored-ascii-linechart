<?php

declare(strict_types=1);

use noximo\PHPColoredAsciiLinechart\Settings;
use noximo\PHPColoredAsciiLinechart\Linechart;
use noximo\PHPColoredAsciiLinechart\Colorizers\AsciiColorizer;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
$settings = new Settings();
$settings->setHeight(30);
$settings->setFPS(120);
$lineGraph = new Linechart();
$lineGraph->setSettings($settings);

try {
    $lineA = [];
    $lineB = [];
    $lineC = [];
    $lineD = [];
    for ($i = 0; $i < 120; $i++) {
        $lineA[$i] = $lineA[$i - 1] ?? 1 + random_int(-2, 2);
        $lineB[$i] = $lineB[$i - 1] ?? 1 + random_int(-2, 2);
        $lineC[$i] = $lineC[$i - 1] ?? 1 + random_int(-2, 2);
        $lineD[$i] = $lineD[$i - 1] ?? 1 + random_int(-2, 2);
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
} catch (Throwable $throwable) {
    echo $throwable->getMessage();
}
