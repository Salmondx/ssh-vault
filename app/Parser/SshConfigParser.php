<?php

namespace App\Parser;

use App\Host;

class SshConfigParser
{
    private const SECTION_PATTERN = '/(\w+)(?:\s*=\s*|\s+)(.+)/';

    /**
     * Parse ssh config content provided as input parameter
     */
    public function parse($fileContent)
    {
        $lines = explode("\n", $fileContent);
        $hosts = [];
        $host = null;
        foreach ($lines as $line) {
            if (empty($line)) {
                continue;
            }

            [$_, $key, $value] = $this->parseLine($line);
            if ($key === 'Host') {
                $host = new Host($value);
                $hosts[] = $host;
            } else {
                $host->addParameter($key, $value);
            }
        }
        return $hosts;
    }

    private function parseLine($line)
    {
        preg_match(self::SECTION_PATTERN, trim($line), $matches);
        return $matches;
    }
}
