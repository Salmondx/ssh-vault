<?php

namespace App\Commands;

use App\SshConfig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListCommand extends Command
{
    protected static $defaultName = 'list';

    protected function configure()
    {
        $this->setHelp('List ssh connections');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $sshConfig = new SshConfig();
        $sshConfig->load();

        if ($sshConfig->isEmpty()) {
            $io->writeln('<info>No Hosts</info>');
            $io->newLine();
            $io->writeln('<info>To add new: </info><comment>ssh-vault add</comment>');
            return;
        }

        $index = 1;
        $io->writeln('<info>Hosts</info>');
        foreach ($sshConfig->hosts() as $host) {
            $name = $host->name;
            $hostName = $host->hostName() ?? 'unknown';
            $io->writeln("[$index] $name (<comment>$hostName</comment>)");
            $index++;
        }
    }
}
