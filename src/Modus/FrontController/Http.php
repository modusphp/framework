<?php

namespace Modus\FrontController;

use Aura\Di\Container;
use Aura\Di\Forge;
use Aura\Di\Config;
use Aura\Web;

class Http {
    
    protected $config;
    protected $di;

    public function __construct($config) {
        $this->setupConfig($config);
        $this->configureAutoloaders();
        $this->di = $this->getDIContainer();

        $this->configureServices();
    }

    protected function setupConfig($config) {
        $this->config = $config;
        foreach($config['directories'] as $directory) {
            if(realpath($directory)) {
                set_include_path(get_include_path() . ":$directory");
            }
        }
    }

    protected function configureServices() {
        $config = $this->config;
        $services = $config['services'];
        foreach($services as $service_key => $service) {

            if(is_callable($service)) {
                $this->di->set($service_key, $service);
            } else {
                $this->di->params[$service['driver']] = $service['params'];
                $this->di->set($service_key, $this->di->lazyNew($service['driver']));
            }
        }
    }
    
    protected function configureAutoloaders() {
        $config = $this->config;
        
        $autoloaders = $config['autoloaders'];
        
        foreach($autoloaders as $file => $class) {
            new $class();
        }
    }
    
    public function getDIContainer() {
        return new Container(new Forge(new Config));
    }
    
    public function execute(array $serverVars = array()) {
        $router = $this->di->get('router');
        $routepath = $router->determineRouting($serverVars);
        if(!$routepath) {
            // Do some kind of 404 here.
            $obj = new \Application\Controller\Error();
            return $obj->error404();
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

        $object = new $callable($this->di, new Web\Context($GLOBALS), new Web\Response);
        $response = $object->exec($action, $params);
        return $this->sendHttpResponse($response);
    }
    
    protected function sendHttpResponse($responseObj) {
        $responseFactory = $this->di->get('response');
        $httpResponse = $responseFactory->getResponse();
        
        // If this is a redirect, let's do the redirect.
        if($responseObj->isRedirect()) {
            $httpResponse->headers->set('Location', $responseObj->getRedirect());
            $httpResponse->setStatusCode($responseObj->getStatusCode());
            $httpResponse->setStatusText($responseObj->getStatustext());
            return $responseFactory->send($httpResponse);
        }
        
        foreach($responseObj->getHeaders() as $header => $value) {
            $httpResponse->headers->set($header, $value);
        }
        
        $httpResponse->setStatusCode($responseObj->getStatusCode());
        $httpResponse->setStatusText($responseObj->getStatusText());
        $httpResponse->setContent($responseObj->getContent());
        $responseFactory->send($httpResponse);
    }
}