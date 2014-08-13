<?php

namespace Modus\Router;

use Aura\Router\Router;

class Standard {
    
    protected $routes;
    protected $router;
    protected $lastRoute;
    
    public function __construct(Router $router, array $routes = array()) {
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
                $result = $this->router->add($key, $route['path']);

                if(isset($route['args']['values'])) {
                    $result->addValues($route['args']['values']);
                }

                if(isset($route['args']['params'])) {
                    $result->addTokens($route['args']['params']);
                }

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
        $result = $this->router->match($path, $serverVars);
        return $result;
    }

    public function getLastRoute() {
        return $this->lastRoute;
    }

    public function getRouter() {
        return $this->router;
    }
}