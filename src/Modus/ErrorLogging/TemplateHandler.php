<?php

namespace Modus\ErrorLogging;

use Exception;

use Aura\View;
use Whoops;
use Whoops\Handler;
use Modus\Common\Controller\Exception as StatusException;

class TemplateHandler implements Handler\HandlerInterface
{
    protected $run;
    protected $exception;
    protected $inspector;

    protected $template;

    public function __construct(View\TwoStep $template, $view) {
        $this->template = $template;
        $this->template->setInnerView($view);
    }


    /**
     * @return int|null  A handler may return nothing, or a Handler::HANDLE_* constant
     */
    public function handle() {

        if($this->exception instanceof StatusException\NotFound) {
            $this->template->setInnerView('status/404.php');
        }

        echo $this->template->render();
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
