<?php

declare(strict_types=1);

use noximo\PHPColoredAsciiLinechart\Settings;
use noximo\PHPColoredAsciiLinechart\Linechart;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
$settings = new Settings();
$settings->setFPS(40);

$lineGraph = new Linechart();
$lineGraph->setSettings($settings);

try {
    $linechart = new Linechart();
    $linechart->addMarkers([1, 1, 1, 1, 1, 1])->chart()->print();
} catch (\Throwable $e) {
    echo $e->getMessage();
}
