<?php

namespace Modus\Controller;

use Aura\Web\Response;
use Aura\Web\Context;
use Aura\View;
use Modus\Session;
use Modus\Common\Model;
use Monolog;

abstract class Base {
    
    protected $session;
    protected $context;
    protected $response;
    protected $template;
    protected $modelFactory;

    protected $action;
    protected $params;

    /**
     * @var Monolog/Logger
     */
    protected $eventlog;

    /**
     * @var Monolog/Logger
     */
    protected $applog;

    public function __construct(
        View\TwoStep $template,
        Session\Aura $session,
        Context $context,
        Response $response,
        Model\Factory $factory,
        Monolog\Logger $eventlog,
        Monolog\Logger $applog
    ) {
        $this->template = $template;
        $this->session = $session;
        $this->context = $context;
        $this->response = $response;
        $this->modelFactory = $factory;
        $this->eventlog = $eventlog;
        $this->applog = $applog;
    }
    
    protected function getResource($resourceName) {
        return $this->di->get($resourceName);
    }

    protected function preAction() {}
    
    protected function postAction() {}
    
    public function exec($action, array $params = [])
    {
        $this->action = $action;
        $this->params = $params;

        try {
            $this->preAction();
            $result = call_user_func_array([$this, $action], $params);
            if(!$result) {
                $result = $this->response;
            }
            $this->postAction();
            return $result;
        } catch (Exception\AuthRequired $authRequired) {
            $this->eventlog->info($authRequired->getMessage());
            return $this->response;
        }
    }

    protected function getModel($model) {
        return $this->modelFactory->newInstance($model);
    }
}