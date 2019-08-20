<?php

declare(strict_types=1);

use noximo\PHPColoredAsciiLinechart\Settings;
use noximo\PHPColoredAsciiLinechart\Linechart;
use noximo\PHPColoredAsciiLinechart\Colorizers\AsciiColorizer;
use noximo\PHPColoredAsciiLinechart\Colorizers\ImageColorizer;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
$lineGraph = new Linechart();
$settings = new Settings();
$settings->setColorizer(new ImageColorizer());
$lineA = [];

for ($i = 0; $i < +120; $i++) {
    $lineA[] = 10 * sin($i * ((M_PI * 4) / 120));
}

$lineGraph->addMarkers($lineA, [AsciiColorizer::WHITE]);

$lineGraph->setSettings($settings);
$graph = $lineGraph->chart();
// Set http headers
header('Content-Type: image/png');

$font = __DIR__ . '/font/font.ttf';
$fontSize = 40;

$text = (string) $graph;

// Calculate the required width to hold this text
$enclosingBox = imagettfbbox($fontSize, 0, $font, $text);
$width = $enclosingBox[2] - $enclosingBox[0] - 10;

$height = $fontSize * $graph->getSettings()->getHeight() * 2;

// Create the image and define colours
$im = imagecreatetruecolor($width + $fontSize, $height + $fontSize);
$white = imagecolorallocate($im, 255, 255, 255);
$grey = imagecolorallocate($im, 0, 0, 0);

imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $grey);

imagettftext($im, $fontSize, 0, $fontSize, $fontSize, $white, $font, $text);

// Output and cleanup
imagepng($im);
imagedestroy($im);
