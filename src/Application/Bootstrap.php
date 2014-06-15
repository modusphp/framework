<?php

namespace Modus\Application;

use Aura\Di;
use Aura\Http;
use Aura\Web;

use Modus\Router;
use Modus\ErrorLogging as Log;
use Modus\Common\Controller\Exception;

class Bootstrap {
    
    protected $config;
    protected $di;
    protected $router;
    protected $responseMgr;
    protected $errorHandler;
    protected $context;

    public function __construct(
        $config,
        Di\Container $di,
        Web\Context $context,
        Router\Standard $router,
        Http\Manager $responseMgr,
        Log\Manager $handler
    ) {
        $this->di = $di;
        $this->context = $context;
        $this->router = $router;
        $this->responseMgr = $responseMgr;
        $this->errorHandler = $handler;

        $this->config = $config;
    }
    
    public function execute() {
        $router = $this->router;
        $routepath = $router->determineRouting($this->context->getServer());
        if(!$routepath) {
            throw new Exception\NotFound('The route "' . $router->getLastRoute() . '" was not found');
        }

        $route = $routepath->values;

        if(isset($route['controller'])) {
            $callable = $route['controller'];
            $params = $route;
            unset($params['controller']);
        } else {
            $module = (isset($route['module'])) ? ucfirst($route['module']) : 'Application';
            $controller = (isset($route['controller'])) ? ucfirst($route['controller']) : 'Index';
            $action = (isset($route['action'])) ? $route['action'] : 'index';

            $callable = "{$module}\\Controller\\{$controller}";

            $params = $route;
            unset($params['controller']);
            unset($params['module']);
            unset($params['action']);
        }

        $object = $this->di->newInstance($callable);
        $response = $object->exec($action, $params);
        $this->sendHttpResponse($response);
    }
    
    protected function sendHttpResponse($controllerResponse) {
        $httpManager = $this->responseMgr;
        $responseMsg = $httpManager->newResponse();

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