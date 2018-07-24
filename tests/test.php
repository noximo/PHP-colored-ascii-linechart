<?php
declare(strict_types = 1);

require_once dirname(__DIR__) . '/src/LineGraph.php';
require_once dirname(__DIR__) . '/src/Config.php';

$config = new noximo\PHPColoredConsoleLinegraph\Config();

$lineGraph = new noximo\PHPColoredConsoleLinegraph\LineGraph();

$config->setHeight(20)->setOffset(10)->setPadding(8);
$lineGraph->chart([1,1500,1,51,51,51,51,48,45,1654,84,6,514,651,84,5,41,854,5], $config);
