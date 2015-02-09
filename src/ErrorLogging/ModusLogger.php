<?php

namespace Modus\ErrorLogging;

use Monolog\Logger as MonologLogger;
use InvalidArgumentException;

abstract class ModusLogger
{
    const EMERGENCY = MonologLogger::EMERGENCY;
    const ALERT     = MonologLogger::ALERT;
    const CRITICAL  = MonologLogger::CRITICAL;
    const ERROR     = MonologLogger::ERROR;
    const WARNING   = MonologLogger::WARNING;
    const NOTICE    = MonologLogger::NOTICE;
    const INFO      = MonologLogger::INFO;
    const DEBUG     = MonologLogger::DEBUG;


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
            throw new InvalidArgumentException('ModusLogger was not found');
        }

        return static::$loggers[$name];
    }

    static public function emergency($message, $context = [], $name = 'default')
    {
        static::log(MonologLogger::EMERGENCY, $message, $context, $name);
    }

    static public function alert($message, $context = [], $name = 'default')
    {
        static::log(MonologLogger::ALERT, $message, $context, $name);
    }

    static public function critical($message, $context = [], $name = 'default')
    {
        static::log(MonologLogger::CRITICAL, $message, $context, $name);
    }

    static public function error($message, $context = [], $name = 'default')
    {
        static::log(MonologLogger::ERROR, $message, $context, $name);
    }

    static public function warning($message, $context = [], $name = 'default')
    {
        static::log(MonologLogger::WARNING, $message, $context, $name);
    }

    static public function notice($message, $context = [], $name = 'default')
    {
        static::log(MonologLogger::NOTICE, $message, $context, $name);
    }

    static public function info($message, $context = [], $name = 'default')
    {
        static::log(MonologLogger::INFO, $message, $context, $name);
    }

    static public function debug($message, $context = [], $name = 'default')
    {
        static::log(MonologLogger::DEBUG, $message, $context, $name);
    }

    static public function log($level, $message, $context = [], $name = 'default')
    {
        if(isset(static::$loggers[$name])) {
            static::$loggers[$name]->log($level, $message, $context);
        }
    }
}