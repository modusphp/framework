<?php

namespace Modus\FrontController;

use Aura\Di;
use Aura\Web;

use Modus\Router;
use Modus\Response\Manager as RespMgr;
use Modus\ErrorLogging as Log;
use Modus\Common\Controller\Exception;

class Http {
    
    protected $config;
    protected $di;
    protected $router;
    protected $responseMgr;
    protected $errorHandler;

    public function __construct($config, Di\Container $di, Router\Standard $router, RespMgr\Factory $responseMgr, Log\Manager $handler) {
        $this->di = $di;
        $this->router = $router;
        $this->responseMgr = $responseMgr;
        $this->errorHandler = $handler;

        $this->setupConfig($config);
        $this->configureAutoloaders();
    }

    protected function setupConfig($config) {
        $this->config = $config;
        foreach($config['directories'] as $directory) {
            if(realpath($directory)) {
                set_include_path(get_include_path() . ":$directory");
            }
        }
    }
    
    protected function configureAutoloaders() {
        $config = $this->config;
        $autoloaders = $config['autoloaders'];
        foreach($autoloaders as $class) {
            new $class();
        }
    }
    
    public function execute(array $serverVars = array()) {
        $router = $this->router;
        $routepath = $router->determineRouting($serverVars);
        if(!$routepath) {
            throw new Exception\NotFound('The route "' . $router->getLastRoute() . '" was not found');
        }
        
        $route = $routepath->values;
        
        $module = (isset($route['module'])) ? ucfirst($route['module']) : 'Application';
        $controller = (isset($route['controller'])) ? ucfirst($route['controller']) : 'Index';
        $action = (isset($route['action'])) ? $route['action'] : 'index';
        
        $callable = "{$module}\\Controller\\{$controller}";
        
        $params = $route;
        unset($params['controller']);
        unset($params['module']);
        unset($params['action']);

        $object = $this->di->newInstance($callable);
        $response = $object->exec($action, $params);
        return $this->sendHttpResponse($response);
    }
    
    protected function sendHttpResponse($controllerResponse) {
        $httpManager = $this->responseMgr;
        $responseMsg = $httpManager->getResponseMessage();

        // If this is a redirect, let's do the redirect.
        if($controllerResponse->isRedirect()) {
            $responseMsg->headers->set('Location', $controllerResponse->getRedirect());
            $responseMsg->setStatusCode($controllerResponse->getStatusCode());
            $responseMsg->setStatusText($controllerResponse->getStatustext());
            return $httpManager->send($responseMsg);
        }
        
        foreach($controllerResponse->getHeaders() as $header => $value) {
            $responseMsg->headers->set($header, $value);
        }
        
        $responseMsg->setStatusCode($controllerResponse->getStatusCode());
        $responseMsg->setStatusText($controllerResponse->getStatusText());
        $responseMsg->setContent($controllerResponse->getContent());
        $httpManager->send($responseMsg);
    }
}