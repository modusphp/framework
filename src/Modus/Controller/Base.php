<?php

namespace Modus\Controller;

use Aura\Di\Container;
use Aura\Web\Response;
use Aura\Web\Context;

abstract class Base {
    
    protected $_di;
    protected $_context;
    protected $_response;
    
    public function __construct(Container $di, Context $context, Response $response) {
        $this->_di = $di;
        $this->_context = $context;
        $this->_response = $response;
    }
    
    protected function authRequired() {
        return [];
    }
    
    protected abstract function checkAuth($action);
    
    protected function _getResource($resourceName) {
        return $this->_di->get($resourceName);
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