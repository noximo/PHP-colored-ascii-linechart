<?php
declare(strict_types = 1);

use noximo\PHPColoredAsciiLinechart\Colorizers\AsciiColorizer;
use noximo\PHPColoredAsciiLinechart\Linechart;
use noximo\PHPColoredAsciiLinechart\Settings;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
$settings = new Settings();
$settings->setFPS(40);
$settings->setHeight(30);
$lineGraph = new Linechart();
$lineGraph->setSettings($settings);

try {
    $line = [];
    for ($i = 0; $i < 120; $i++) {
        $line[$i] = ($lineA[$i - 1] ?? 1) + (lcg_value() * random_int(-1, 1));
    }
    for ($y = 0; $y < 1500; $y++) {
        array_shift($line);
        $line[] = end($line) + (lcg_value() * random_int(-1, 1));
        $lineGraph->addMarkers($line, [AsciiColorizer::GREEN], [AsciiColorizer::RED]);

        //$lineGraph->addLine($line[0] * 1.1, [AsciiColorizer::CYAN], Linechart::FULL_LINE);

        $lineGraph->chart()->clearScreen()->print()->wait();
        $lineGraph->clearAllMarkers();
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
