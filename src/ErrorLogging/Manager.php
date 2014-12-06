<?php

namespace Modus\ErrorLogging;

use Monolog;
use Savage\BooBoo\Runner;

class Manager {

    protected $loggers;
    protected $errorHandler;


    public function __construct(
        Runner $runner,
        array $loggers = array()
    )
    {
        $runner->register();

        $this->errorHandler = $runner;
        $this->loggers = $loggers;
    }

    public function getErrorHandler() {
        return $this->errorHandler;
    }

    public function getLogger($loggerName = null) {

        if(empty($loggerName)) {
            return $this->loggers;
        }

        if (isset($this->loggers[$loggerName])) {
            return $this->loggers[$loggerName];
        }

        throw new \Exception(sprintf('Logger %s is not registered', $loggerName));
    }
}