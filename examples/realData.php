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
    $line = [0.0208, 0.020858, 0.021, 0.021, 0.0211, 0.0211, 0.0211, 0.0211, 0.0211, 0.021056, 0.0211, 0.0211, 0.0211, 0.0211, 0.0211, 0.0211, 0.0211, 0.0211, 0.021124, 0.0214, 0.0215, 0.021436, 0.02149, 0.021488, 0.02149, 0.02145, 0.02145, 0.021406, 0.02145, 0.02145, 0.02145, 0.0214, 0.02145, 0.021487, 0.02149, 0.021482, 0.02148, 0.02148, 0.0215, 0.0215, 0.0215, 0.0215, 0.021499, 0.021473, 0.021454, 0.021497, 0.021489, 0.021454, 0.021705, 0.02151, 0.021513];

    $lineGraph->addMarkers($line, [AsciiColorizer::GREEN], [AsciiColorizer::RED]);

    $lineGraph->chart()->clearScreen()->print()->wait();
    $lineGraph->clearAllMarkers();
} catch (\Throwable $e) {
    echo $e->getMessage();
}
