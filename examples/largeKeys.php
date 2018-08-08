<?php
declare(strict_types = 1);

use noximo\PHPColoredAsciiLinechart\Colorizers\AsciiColorizer;
use noximo\PHPColoredAsciiLinechart\Linechart;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
$lineGraph = new Linechart();

$lineA = [
    14536 => 2,
    14537 => 2,
    14538 => 8,
    14539 => 8,
    14540 => 4,
    14541 => 4,
    14542 => 8,
    14543 => 8,
    14544 => 8,
    14545 => 8,
    14546 => 2,
    14547 => 2,
    14548 => 2,
    14549 => 2,
];
$lineGraph->addMarkers($lineA, [AsciiColorizer::GREEN, AsciiColorizer::BOLD]);

echo $lineGraph->chart();
