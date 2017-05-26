<?php

namespace Yousign;

use Yousign\Exception\EnvironmentException;

class Environment
{
    const PROD = 'prod';
    const DEMO = 'demo';

    /**
     * @var array
     */
    private $mappingHosts = array(
        self::DEMO => 'https://apidemo.yousign.fr:8181',
        self::PROD => 'https://api.yousign.fr:8181',
    );

    /**
     * @var bool
     */
    private $sslEnable = true;

    /**
     * @var string
     */
    private $environment;

    /**
     * Environment constructor.
     * @param null $environment
     * @throws EnvironmentException
     */
    public function __construct($environment = null)
    {
        if(!isset($this->mappingHosts[$environment])) {
            throw new EnvironmentException('Given environment is not correct');
        }

        $this->environment = $environment;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->mappingHosts[$this->environment];
    }

    /**
     * @return boolean
     */
    public function isSslEnable()
    {
        return $this->sslEnable;
    }

    /**
     * @param boolean $sslEnable
     */
    public function setSslEnable($sslEnable)
    {
        $this->sslEnable = $sslEnable;
    }
}