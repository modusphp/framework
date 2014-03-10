<?php

namespace Modus\ErrorLogging;

use Exception;

use Aura\View;
use Whoops;
use Whoops\Handler;
use Monolog;
use Modus\Common\Controller\Exception as StatusException;


class MonologHandler implements Handler\HandlerInterface
{
    protected $run;
    protected $exception;
    protected $inspector;

    protected $logger;

    public function __construct(Monolog\Logger $logger) {
        $this->logger = $logger;
    }


    /**
     * @return int|null  A handler may return nothing, or a Handler::HANDLE_* constant
     */
    public function handle() {

        if($this->exception instanceof StatusException\NotFound) {
            $this->logger->info($this->exception->getMessage());
            return;
        }

        $this->logger->error($this->exception->getMessage() . $this->exception->getTraceAsString());
    }

    /**
     * @param Run $run
     */
    public function setRun(Whoops\Run $run) {
        $this->run = $run;
    }

    /**
     * @param Exception $exception
     */
    public function setException(Exception $exception) {
        $this->exception = $exception;
    }

    /**
     * @param Inspector $inspector
     */
    public function setInspector(Whoops\Exception\Inspector $inspector) {
        $this->inspector = $inspector;
    }
}
