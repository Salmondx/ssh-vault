<?php

namespace App\Commands;

use App\Host;
use App\SshConfig;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AddCommand extends Command
{
    protected static $defaultName = 'add';

    protected function configure()
    {
        $this->setHelp('Add new ssh connection');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $sshConfig = new SshConfig();
        $sshConfig->load();

        $lastHost = $this->lastHost($sshConfig);
        $name = $io->ask('Connection name', null, $this->validateNotEmpty());
        $address = $io->ask('Host address (ip or dns)', null, $this->validateNotEmpty());
        $port = $io->ask(
            'Port',
            22,
            function ($number) {
                if (!is_numeric($number)) {
                    throw new \RuntimeException('You must type a number.');
                }

                return (int) $number;
            }
        );
        $user = $io->ask(
            'Username (<comment>none</comment> if empty)',
            $lastHost ? $lastHost->getParameter('User') : ''
        );
        $identityFile = $io->ask(
            'Identity file location (<comment>none</comment> if empty)',
            $lastHost ? $lastHost->getParameter('IdentityFile') : '',
            $this->validateFileExists()
        );
        $forwardAgent = $io->confirm(
            'Forward agent',
            $lastHost ? $lastHost->getParameter('ForwardAgent') == 'yes' : false
        );

        $host = new Host($name);
        $host->addParameter('HostName', $address);
        $host->addParameter('Port', $port);
        if (!$this->isNone($user)) {
            $host->addParameter('Username', $user);
        }
        if (!$this->isNone($identityFile)) {
            $host->addParameter('IdentityFile', $identityFile);
        }
        if (!$this->isNone($forwardAgent)) {
            $host->addParameter('ForwardAgent', $forwardAgent ? 'yes' : 'no');
        }

        $sshConfig->add($host);
        $sshConfig->sync();
    }

    private function lastHost(SshConfig $sshConfig)
    {
        if ($sshConfig->isEmpty()) {
            return;
        }

        $hosts = $sshConfig->hosts();
        return $hosts[array_key_last($hosts)];
    }

    /**
     * Check if negative option provided
     */
    private function isNone($value)
    {
        $value = strtolower($value);
        return $value == 'none' || $value == 'no' || $value == 0;
    }

    private function validateFileExists()
    {
        return function ($path) {
            if (!$this->isNone($path) && !file_exists($path)) {
                throw new RuntimeException("$path path not exists");
            }
            return $path;
        };
    }

    private function validateNotEmpty()
    {
        return function ($value) {
            if (empty($value)) {
                throw new \RuntimeException('Empty value is not allowed');
            }
            return $value;
        };
    }
}
