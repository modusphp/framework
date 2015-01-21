<?php

namespace Modus\ErrorLogging;

use Monolog\Logger as MonologLogger;
use InvalidArgumentException;

abstract class Logger
{
    static protected $loggers = [];

    static public function registerLogger(MonologLogger $logger, $name = 'default')
    {
        static::$loggers[$name] = $logger;
    }

    static public function deregisterLogger(MonologLogger $logger)
    {
        foreach (static::$loggers as $key => $loggerInstance) {
            if($loggerInstance === $logger) {
                unset(static::$loggers[$key]);
                return;
            }
        }

        throw new \InvalidArgumentException('The logger could not be found');
    }

    static public function getLogger($name)
    {
        if(!isset(static::$loggers[$name])) {
            throw new InvalidArgumentException('Logger was not found');
        }

        return static::$loggers[$name];
    }

    static public function emergency($message, $context = [], $name = 'default')
    {
        static::log(MonologLogger::EMERGENCY, $message, $context, $name);
    }

    static public function alert($message, $context = [], $name = 'default')
    {
        static::log(MonologLogger::EMERGENCY, $message, $context, $name);
    }

    static public function critical($message, $context = [], $name = 'default')
    {
        static::log(MonologLogger::EMERGENCY, $message, $context, $name);
    }

    static public function error($message, $context = [], $name = 'default')
    {
        static::log(MonologLogger::EMERGENCY, $message, $context, $name);
    }

    static public function warning($message, $context = [], $name = 'default')
    {
        static::log(MonologLogger::EMERGENCY, $message, $context, $name);
    }

    static public function notice($message, $context = [], $name = 'default')
    {
        static::log(MonologLogger::EMERGENCY, $message, $context, $name);
    }

    static public function info($message, $context = [], $name = 'default')
    {
        static::log(MonologLogger::EMERGENCY, $message, $context, $name);
    }

    static public function debug($message, $context = [], $name = 'default')
    {
        static::log(MonologLogger::EMERGENCY, $message, $context, $name);
    }

    static public function log($level, $message, $context = [], $name = 'default')
    {
        if(isset(static::$loggers[$name])) {
            static::$loggers[$name]->log($level, $message, $context);
        }
    }
}