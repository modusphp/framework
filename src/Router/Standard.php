<?php

namespace Modus\Router;

use Aura\Router\Map;
use Aura\Router\DefinitionFactory;
use Aura\Router\RouteFactory;

class Standard {
    
    protected $routes;
    protected $router;
    protected $lastRoute;
    
    public function __construct(Map $router, array $routes = array()) {
        $this->router = $router;
        $this->routes = $routes;
        $this->configureRouter();
    }
    
    protected function configureRouter() {
        foreach($this->routes as $k => $route) {
            $key = null;
            // Keys can be named routes
            if(!is_int($k)) {
                $key = $k;
            }
            
            if(is_array($route)) {
                $this->router->add($key, $route['path'], $route['args']);
            } else {
                $this->router->add($key, $route);
            }            
        }
    }
    
    public function determineRouting(array $serverVars = array())
    {
        if(!isset($serverVars['REQUEST_URI'])) {
            return false;
        }

        $path = parse_url($serverVars['REQUEST_URI'], PHP_URL_PATH);
        $this->lastRoute = $path;
        return $this->router->match($path, $serverVars);
    }

    public function getLastRoute() {
        return $this->lastRoute;
    }
    
}