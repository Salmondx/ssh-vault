<?php

namespace App;

use App\Commands\AddCommand;
use App\Commands\ListCommand;
use App\Commands\RawCommand;
use App\Commands\RemoveCommand;
use Symfony\Component\Console\Application;

class Main
{
    public function run()
    {
        $application = new Application();
        $application->add(new AddCommand());
        $application->add(new ListCommand());
        $application->add(new RawCommand());
        $application->add(new RemoveCommand());
        $application->run();
    }
}
