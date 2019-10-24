<?php

namespace App;

/**
 * Ssh Host representation
 */
class Host
{
    public $name;
    public $config = [];

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Add new parameter to ssh config
     */
    public function addParameter($name, $value)
    {
        $this->config[$name] = $value;
    }

    /**
     * Get parameter from config
     */
    public function getParameter($parameter)
    {
        return $this->config[$parameter] ?? null;
    }

    /**
     * Get HostName parameter from config
     */
    public function hostName()
    {
        return $this->getParameter('HostName');
    }

    public function __toString()
    {
        $result = "Host {$this->name}\n";
        foreach ($this->config as $key => $value) {
            $result .= "  $key $value\n";
        }
        return $result;
    }
}
