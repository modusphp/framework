<?php

namespace Modus\ErrorLogging;

use League\BooBoo\BooBoo;
use Monolog;
use Aura\Accept\Accept;

class Manager implements ManagerInterface
{

    /**
     * @var array
     */
    protected $loggers;

    /**
     * @var BooBoo
     */
    protected $errorHandler;


    /**
     * @param BooBoo $runner
     * @param array  $loggers
     */
    public function __construct(
        BooBoo $runner,
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

        foreach ($loggers as $name => $logger) {
            if ($name == 'event') {
                ModusLogger::registerLogger($logger);
            } else {
                ModusLogger::registerLogger($logger, $name);
            }
        }
    }

    /**
     * @param  $bool
     * @throws \League\BooBoo\Exception\NoFormattersRegisteredException
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
     * @return BooBoo
     */
    public function getErrorHandler()
    {
        return $this->errorHandler;
    }

    /**
     * @param  null $loggerName
     * @return Monolog\Logger|array;
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
