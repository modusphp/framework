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

    /**
     * @var Monolog/Logger
     */
    protected $eventlog;

    /**
     * @var Monolog/Logger
     */
    protected $applog;

    /**
     * @var Monolog\Logger
     */
    protected $logger;

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
    
    protected function authRequired() {
        return [];
    }
    
    protected abstract function checkAuth($action);
    
    protected function getResource($resourceName) {
        return $this->di->get($resourceName);
    }
    
    protected function preExec() {}
        
    protected function preAction() {}
    
    protected function postAction() {}
        
    protected function preRender() {}
        
    protected function render() {}
    
    protected function postRender() {}
        
    protected function postExec() {}
    
    public function exec($action, array $params = [])
    {
        $this->preExec();
        $this->preAction();
        $badAuth = $this->checkAuth($action);
        
        // If auth required but not provided, return a response object.
        // That object should be configured with a redirect to login.
        if($badAuth) {
            return $badAuth;
        }
        
        $result = call_user_func_array([$this, $action], $params);
        $this->postAction();
        $this->preRender();
        $this->render();
        $this->postRender();
        $this->postExec();
        return $result;
    }

    protected function getModel($model) {
        return $this->modelFactory->newInstance($model);
    }
}