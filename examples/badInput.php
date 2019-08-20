<?php

declare(strict_types=1);

use noximo\PHPColoredAsciiLinechart\Linechart;
use noximo\PHPColoredAsciiLinechart\Colorizers\AsciiColorizer;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
$lineGraph = new Linechart();

$lineA = [
    1 => 2,
    9 => 2,
    'a' => 12,
    2 => 8,
    15 => 4,
    14 => 8,
    8 => 2,
    3 => 2,
    4 => 2,
];
$lineGraph->addMarkers($lineA, [AsciiColorizer::GREEN, AsciiColorizer::BOLD], [AsciiColorizer::RED]);

$lineGraph->chart()->print();
