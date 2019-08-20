<?php

declare(strict_types=1);

use noximo\PHPColoredAsciiLinechart\Settings;
use noximo\PHPColoredAsciiLinechart\Linechart;
use noximo\PHPColoredAsciiLinechart\Colorizers\HTMLColorizer;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
$lineGraph = new Linechart();
$settings = new Settings();
$settings->setColorizer(new HTMLColorizer());
$lineA = [];

for ($i = 0; $i < +120; $i++) {
    $lineA[] = 10 * sin($i * ((M_PI * 4) / 120));
}

$lineGraph->addLine(0, ['color:black'], Linechart::FULL_LINE);
$lineGraph->addMarkers($lineA, ['color: green'], ['color: red']);
$lineGraph->setSettings($settings);

$lineGraph->chart()->print();
