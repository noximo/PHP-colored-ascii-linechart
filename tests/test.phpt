<?php

use Tester\Assert;


require __DIR__ . '/../vendor/autoload.php';       # při instalaci Composerem

require __DIR__ . '/../src/LineGraph.php';
require __DIR__ . '/../src/Config.php';

Tester\Environment::setup();


Assert::same('Hello John', $o->say('John'));  # Očekáváme shodu

Assert::exception(function() use ($o) {       # Očekáváme výjimku
$o->say('');
}, InvalidArgumentException::class, 'Invalid name');