<?php

declare(strict_types=1);

use noximo\PHPColoredAsciiLinechart\Settings;
use noximo\PHPColoredAsciiLinechart\Linechart;
use noximo\PHPColoredAsciiLinechart\Colorizers\AsciiColorizer;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$settings = new Settings();
//Note that any setting can be ommited.
$settings
    ->setColorizer(new AsciiColorizer())//Colorizer, choose between Ascii, HTML and image colorizers
    ->setFPS(24)//control speed of Graph::wait method
    ->setHeight(30)//Set fixed height of graph. Graph will scale accordingly
    ->setPadding(5, ' ')//Set lenght of a padding and character used
    ->setOffset(10)//Offset left border
    ->setFormat( //control how y axis labels will be printed out
        function ($x, Settings $settings) {
            $padding = $settings->getPadding();
            $paddingLength = strlen($padding);

            return substr($padding . round($x, 2), -$paddingLength);
        }
    );

$lineGraph = new Linechart();
$lineGraph->setSettings($settings);

for ($y = 0; $y < 40000; $y++) { //Move sinusoid
    $lineA = [];
    $lineB = [];
    for ($i = $y; $i < $y + 120; $i++) {
        $lineA[] = 10 * sin($i * ((M_PI * 4) / 120)); //Draw sinusoid
        $lineB[] = 20 * sin($i * ((M_PI * 4) / 120)); //Draw sinusoid
    }

    $lineGraph->addMarkers(
        $lineA, //graph data - note that any elements with non-integer keys will be discarded
        [AsciiColorizer::GREEN, AsciiColorizer::BOLD], // Default color of line. Can be ommited. You can combine mutliple color codes together. If you set up HTML colorizer, you can enter css styles instead of codes. See below
        [AsciiColorizer::RED, AsciiColorizer::BOLD] // Color of downtrend. Can be ommited, then default color will be used instead.
    );
    //Pro-tip - combine color with bold style - it will pop-out nicely

    $lineGraph->addMarkers($lineB, [AsciiColorizer::CYAN]); //Add as many datasets as you want

    $lineGraph->addLine( //Add a guiding line - a zero line for example
        0, //Alias y coordinate
        [AsciiColorizer::CYAN], //You can set color the same way as with series
        Linechart::FULL_LINE //Choose between full line and
    );

    $lineGraph->addPoint(
        10,
        15,
        [AsciiColorizer::LIGHT_BLUE],
        Linechart::CROSS //Point can be made more visible with crosslines. Default is Linegraph::POINT
    );

    $graph = $lineGraph->chart(); //Graph is an object with all data drawn. It can be simply echoed or we can leverage its methods for output control

    $graph->clearScreen(); //clears already outputed graphs
    $graph->print(); //Alias of echo $graph with fluent method call
    $graph->wait(); //Naive implementation of animation. It simply sleeps for n microseconds (defined by setFPS earlier). It does not take into account time spent on graph generation or on retrieving data
    $lineGraph->clearAllMarkers(); //Get rid of already processed graph data so they won't get printed again.
}
