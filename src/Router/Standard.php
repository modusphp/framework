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
        foreach($this->routes as $routeName => $route) {

            // defaults
            $params = [];
            $secure = false;
            $request = 'HEAD|GET|DELETE|OPTIONS|PATCH|POST|PUT';

            $path = $route['path'];

            $values = $route['values'];

            if (isset($route['params'])) {
                $params = $route['params'];
            }

            if (isset($route['secure'])) {
                $secure = $route['secure'];
            }

            if (isset($route['request'])) {
                $request = $route['request'];
            }

            $router = $this->router;
            $router->add($routeName, $path)
                   ->addValues($values)
                   ->addTokens($params)
                   ->setSecure($secure)
                   ->addServer(['REQUEST_METHOD' => $request]);
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