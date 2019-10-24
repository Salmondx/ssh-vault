<?php

namespace App\Commands;

use App\SshConfig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RemoveCommand extends Command
{
    protected static $defaultName = 'remove';

    protected function configure()
    {
        $this->setHelp('Remove ssh connection');
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

        $userIndex = 1;
        $io->writeln('<info>Hosts</info>');
        foreach ($sshConfig->hosts() as $host) {
            $name = $host->name;
            $hostName = $host->hostName() ?? 'unknown';
            $io->writeln("[$userIndex] $name (<comment>$hostName</comment>)");
            $userIndex++;
        }

        $lastIndex = $userIndex - 1;
        $connectionToDelete = $io->ask(
            "Connection to remove (number from <comment>1 to $lastIndex</comment>)",
            null,
            function ($number) use ($lastIndex) {
                if (!is_numeric($number)) {
                    throw new \RuntimeException("Provide a number from 1 to $lastIndex");
                }
                if ($number < 1 || $number > $lastIndex) {
                    throw new \RuntimeException("Number should be between 1 and $lastIndex");
                }
                return (int) $number;
            }
        );

        $sshConfig->remove($connectionToDelete - 1);
        $sshConfig->sync();
    }
}
