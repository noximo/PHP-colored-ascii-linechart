<?php
declare(strict_types = 1);

use noximo\PHPColoredAsciiLinechart\Colorizers\AsciiColorizer;
use noximo\PHPColoredAsciiLinechart\Linechart;
use noximo\PHPColoredAsciiLinechart\Settings;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
$settings = new Settings();
$settings->setFPS(40);
$settings->setHeight(9);

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

} catch (Exception $e) {
    echo $e->getMessage();
}
