<?php

declare(strict_types=1);

use noximo\PHPColoredAsciiLinechart\Linechart;
use noximo\PHPColoredAsciiLinechart\Settings;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
$settings = new Settings();
$settings->setFPS(40);
$settings->setHeight(null);

$lineGraph = new Linechart();
$lineGraph->setSettings($settings);

try {
    $linechart = new Linechart();
    $linechart->addMarkers([1, 1, 1, 1, 1, 1])->chart()->print();
} catch (Exception $e) {
    echo $e->getMessage();
}
