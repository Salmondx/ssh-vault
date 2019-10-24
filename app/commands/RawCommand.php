<?php

namespace App\Commands;

use App\SshConfig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RawCommand extends Command
{
    protected static $defaultName = 'raw';

    protected function configure()
    {
        $this->setHelp('Show raw ssh config content');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $sshConfig = new SshConfig();
        $sshConfig->load();

        foreach ($sshConfig->hosts() as $host) {
            $io->writeln($host);
        }
    }
}
