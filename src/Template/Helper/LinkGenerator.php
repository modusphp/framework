<?php

namespace Modus\Template\Helper;

use Aura\View\Helper\AbstractHelper;
use Aura\Router\Map;

class LinkGenerator extends AbstractHelper {

    protected $routes;
    protected $router;
    protected $lastRoute;

    public function __construct(Map $router, array $routes = array()) {
        $this->router = $router;
        $this->routes = $routes;
        $this->configureRouter();
    }

    public function __invoke($routeName, array $arguments = array()) {
        return $this->router->generate($routeName, $arguments);
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
}