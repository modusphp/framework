<?php

namespace Modus\FrontController;

use Modus\Response;
use Aura\Di\Container;
use Aura\Di\Forge;
use Aura\Di\Config;
use Aura\Sql\ConnectionFactory;
use Aura\Web;

class Http {
    
    private $_config;
    protected $_session;
    protected $_db;
    protected $_router;
    protected $_request;
    protected $_di;
    protected $_response;
    
    public function __construct($config) {
        $this->_setupConfig($config);
        $this->_configureAutoloaders();
        $this->_di = $this->_getDIContainer();
        $this->_router = $this->_loadRouter();

        $this->_db = $this->_loadDb();
        $this->_loadRequest();
        $this->_configureSession();
        $this->_configureTemplate();
        $this->_configureResponse();
    }
    
    protected function _configureResponse() {
        $r = new Response\Factory();
        $this->_response = $r;
    }
    
    protected function _configureTemplate() {
        $config = $this->_config['views'];
        $view = new \Modus\Template\Factory($config['layout'], $config['view_paths']);
        $this->_di->set('template', $view->getTemplate());
    }
    
    protected function _configureAutoloaders() {
        $config = $this->_config;
        
        $autoloaders = $config['autoloaders'];
        
        foreach($autoloaders as $file => $class) {
            new $class();
        }
    }
    
    public function _getDIContainer() {
        return new Container(new Forge(new Config));
    }
    
    protected function _loadRequest() {
        $request = $this->_config['request'];
        $this->_di->set('request', new $request);
    }
    
    protected function _loadRouter() {
        $router = $this->_config['routing']['router'];
        return new $router($this->_config);
    }
    
    protected function _loadDb() {
       if(isset($this->_config['database']) && !empty($this->_config['database'])) {
           $connection_factory = new ConnectionFactory;
           $di = $this->_di;
           foreach($this->_config['database'] as $name => $config) {
               $di->set($name, $connection_factory->newInstance(
                            $config['adapter'],
                            array('host' => $config['host'], 'dbname' => $config['name']),
                            $config['user'],
                            $config['pass']
                        ));
           }
       }
    }
    
    public function execute(array $serverVars = array()) {
        $routepath = $this->_router->determineRouting($serverVars);
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

        $object = new $callable($this->_di, new Web\Context($GLOBALS), new Web\Response);
        $response = $object->exec($action, $params);
        return $this->renderResponse($response);
    }
    
    protected function renderResponse($responseObj) {
        $response = $this->_response->getResponse();
        
        // If this is a redirect, let's do the redirect.
        if($responseObj->isRedirect()) {
            $response->headers->set('Location', $responseObj->getRedirect());
            $response->setStatusCode($responseObj->getStatusCode());
            $response->setStatusText($responseObj->getStatustext());
            return $this->_response->send($response);
        }
        
        foreach($responseObj->getHeaders() as $header => $value) {
            $response->headers->set($header, $value);
        }
        
        $response->setStatusCode($responseObj->getStatusCode());
        $response->setStatusText($responseObj->getStatusText());
        $response->setContent($responseObj->getContent());
        $this->_response->send($response);
    }
    
    protected function _configureSession() {
        $config = $this->_config;
        $session_config = $config['session_config'];
        $driver = $session_config['driver'];
        $this->_session = new $driver();
        $this->_di->set('session', $this->_session->getInstance());
    }
    
    protected function _setupConfig($config) {
        $this->_config = $config;
        foreach($config['directories'] as $directory) {
            var_dump($directory);
            if(realpath($directory)) {
                set_include_path(get_include_path() . ":$directory");
            }
        }
    } 
    
    protected function _setupLogger() {}
        
    protected function _setupErrorHandlers() {}
}