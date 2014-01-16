<?php

namespace Modus\Router;

use Aura\Router\Map;
use Aura\Router\DefinitionFactory;
use Aura\Router\RouteFactory;

class Standard {
    
    protected $config;
    protected $_router;
    
    public function __construct(array $config = array()) {
        $this->config = $config;
        $this->_configureRouter();
    }
    
    protected function _configureRouter() {
        $this->_router = new Map(new DefinitionFactory, new RouteFactory);
        foreach($this->config['routing']['routes'] as $k => $route) {
            $key = null;
            // Keys can be named routes
            if(!is_int($k)) {
                $key = $k;
            }
            
            if(is_array($route)) {
                $this->_router->add($key, $route['path'], $route['args']);
            } else {
                $this->_router->add($key, $route);
            }            
        }
    }
    
    public function determineRouting(array $serverVars = array())
    {
        if(!isset($serverVars['REQUEST_URI'])) {
            return false;
        }
        
        $path = parse_url($serverVars['REQUEST_URI'], PHP_URL_PATH);
        return $this->_router->match($path, $serverVars);
    }
    
}