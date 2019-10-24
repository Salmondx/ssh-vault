<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Commands\AddCommand;
use App\Commands\ListCommand;
use App\Commands\RawCommand;
use App\Commands\RemoveCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new AddCommand());
$application->add(new ListCommand());
$application->add(new RawCommand());
$application->add(new RemoveCommand());
$application->run();
