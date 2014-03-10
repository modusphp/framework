<?php

namespace Modus\ErrorLogging;

use Monolog;
use Whoops;

class Manager {

    protected $logger;
    protected $whoops;


    public function __construct(
        Monolog\Logger $logger,
        Whoops\Run $whoops,
        array $loggers = array(),
        array $handlers = array()
    ) {

        foreach($loggers as $log_handler) {
            $logger->pushHandler($log_handler);
        }

        foreach($handlers as $handler) {
            $whoops->pushHandler($handler());
        }

        $whoops->register();

        $this->whoops = $whoops;
        $this->logger = $logger;
    }

    public function getErrorHandler() {
        return $this->whoops;
    }

    public function getLogger() {
        return $this->logger;
    }
}