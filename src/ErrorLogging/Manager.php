<?php

namespace Modus\ErrorLogging;

use Monolog;
use Savage\BooBoo\Runner;
use Aura\Accept\Accept;

class Manager implements ManagerInterface
{

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
     */
    public function __construct(
        Runner $runner,
        Accept $accept,
        array $loggers = array(),
        array $availableFormatters = array()
    ) {
        $this->errorHandler = $runner;
        $this->loggers = $loggers;

        if ($availableFormatters) {
            $possibleAccepts = array_keys($availableFormatters);
            $result = $accept->negotiateMedia($possibleAccepts);
            if ($result) {
                $this->errorHandler->pushFormatter($availableFormatters[$result->getValue()]);
            }
        }

        foreach($loggers as $name => $logger) {
            if($name == 'event') {
                ModusLogger::registerLogger($logger);
            } else {
                ModusLogger::registerLogger($logger, $name);
            }
        }
    }

    /**
     * @param $bool
     * @throws \Savage\BooBoo\Exception\NoFormattersRegisteredException
     */
    public function registerErrorHandler($bool)
    {
        if ($bool) {
            $this->errorHandler->register();
        } else {
            $this->errorHandler->deregister();
        }
    }

    /**
     * @return Runner
     */
    public function getErrorHandler()
    {
        return $this->errorHandler;
    }

    /**
     * @param null $loggerName
     * @return Monolog\Logger;
     * @throws Exception\LoggerNotRegistered
     */
    public function getLogger($loggerName = null)
    {

        if (empty($loggerName)) {
            return $this->loggers;
        }

        if (isset($this->loggers[$loggerName])) {
            return $this->loggers[$loggerName];
        }

        throw new Exception\LoggerNotRegistered(sprintf('ModusLogger %s is not registered', $loggerName));
    }
}
