<?php

declare(strict_types=1);

use noximo\PHPColoredAsciiLinechart\Settings;
use noximo\PHPColoredAsciiLinechart\Linechart;
use noximo\PHPColoredAsciiLinechart\Colorizers\AsciiColorizer;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
$lineGraph = new Linechart();
$settings = new Settings();
$settings->setFPS(120);

for ($y = 0; $y < 1; $y++) {
    $lineA = [];
    for ($i = $y; $i < $y + 120; $i++) {
        $lineA[] = 1 * sin($i * ((M_PI * 4) / 120));
    }

    $lineGraph->addMarkers($lineA, [AsciiColorizer::GREEN, AsciiColorizer::BOLD], [AsciiColorizer::RED]);

    $lineGraph->setSettings($settings);
    $lineGraph->chart()->clearScreen()->print()->wait();
    $lineGraph->clearAllMarkers();
}
