<?php

namespace Modus\Config;

use Aura\Di\Container;

class Config {

    const ENV_DEV = 'dev';
    const ENV_STAGING = 'staging';
    const ENV_PRODUCTION = 'production';
    const ENV_TESTING = 'testing';

    protected $environments = [
        self::ENV_DEV,
        self::ENV_STAGING,
        self::ENV_TESTING,
        self::ENV_PRODUCTION,
    ];

    protected $environment;
    protected $configDir;
    protected $di;
    protected $configs = [
        'config.php',
        'dev.php',
        'staging.php',
        'production.php',
        'local.php',
        'testing.php',
    ];

    protected $fileList = [];

    protected $config = [];


    public function __construct($env, $configDir, Container $di) {
        $this->environment = $this->validateEnvironment($env);
        $this->configDir = realpath($configDir);
        $this->di = $di;

        $this->loadFileList($this->configDir);
        $this->loadConfiguration($configDir);
        $this->loadDependencies($di);
    }

    public function addExcludedFile($fileName) {
        if(is_string($fileName)) {
            array_push($this->configs, $fileName);
        }
        return $this;
    }

    public function getConfig() {
        return $this->config;
    }

    public function getDI() {
        return $this->di;
    }

    protected function validateEnvironment($env) {
        if(!in_array($env, $this->environments)) {
            throw new \Exception(sprintf('%s is an invalid environment', $env));
        }

        return $env;
    }

    protected function loadFileList($configDir) {
        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($configDir));

        while($it->valid()) {

            if (!$it->isDot()) {
                if ($it->getExtension() == 'php') {
                    $this->fileList[] = $it->getFilename();
                }
            }
            $it->next();
        }
    }

    protected function loadConfiguration() {

        $config = [];
        $env_config = $this->environment . '.php';
        if(in_array('config.php', $this->fileList)) {
            $config = array_merge($config, require($this->configDir . '/config.php'));
        }

        if(in_array($env_config, $this->fileList)) {
            $config = array_merge($config, require($this->configDir . '/' . $env_config));
        }

        if(in_array('local.php', $this->fileList)) {
            $config = array_merge($config, require($this->configDir . '/local.php'));
        }

        $this->config = $config;
        return $config;

    }

    protected function loadDependencies(Container $di) {
        $config = $this->config;

        foreach($this->fileList as $file) {
            if(!in_array($file, $this->configs)) {
                require_once $this->configDir . '/' . $file;
            }
        }
    }


}