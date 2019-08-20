<?php

declare(strict_types=1);

use noximo\PHPColoredAsciiLinechart\Settings;
use noximo\PHPColoredAsciiLinechart\Linechart;
use noximo\PHPColoredAsciiLinechart\Colorizers\AsciiColorizer;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
$settings = new Settings();
$settings->setFPS(40);

$lineGraph = new Linechart();
$lineGraph->setSettings($settings);

try {
    $line = [];
    for ($i = 0; $i < 50; $i++) {
        $line[$i] = $i;
    }

    $lineGraph->addMarkers($line, [AsciiColorizer::GREEN], [AsciiColorizer::RED]);

    $lineGraph->chart()->clearScreen()->print()->wait();
    $lineGraph->clearAllMarkers();
} catch (\Throwable $e) {
    echo $e->getMessage();
}
