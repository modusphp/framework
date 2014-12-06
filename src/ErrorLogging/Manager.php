<?php

namespace Modus\ErrorLogging;

use Monolog;
use Savage\BooBoo\Runner;

class Manager {

    /**
     * @var array
     */
    protected $loggers;

    /**
     * @var Runner
     */
    protected $errorHandler;


    /**
     * @param Runner $runner
     * @param array $loggers
     * @throws \Savage\BooBoo\Exception\NoFormattersRegisteredException
     */
    public function __construct(
        Runner $runner,
        array $loggers = array()
    )
    {
        $runner->register();

        $this->errorHandler = $runner;
        $this->loggers = $loggers;
    }

    /**
     * @return Runner
     */
    public function getErrorHandler() {
        return $this->errorHandler;
    }

    /**
     * @param null $loggerName
     * @return Monolog\Logger;
     * @throws \Exception
     */
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