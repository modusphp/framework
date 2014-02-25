<?php

namespace Modus\Controller;

use Aura\Di\Container;
use Aura\Web\Response;
use Aura\Web\Context;

abstract class Base {
    
    protected $di;
    protected $context;
    protected $response;
    
    public function __construct(Container $di, Context $context, Response $response) {
        $this->di = $di;
        $this->context = $context;
        $this->response = $response;
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
        
    protected function render() {
        
    }
    
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
}