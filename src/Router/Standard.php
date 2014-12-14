<?php

namespace Modus\Router;

use Aura\Router\Router;

class Standard
{

    /**
     * @var array The routes that we are registering.
     */
    protected $routes;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var string|null The last route we attempted to load.
     */
    protected $lastRoute = null;

    /**
     * @var An array of the $_SERVER vars.
     */
    protected $serverVars = array();

    /**
     * @param Router $router
     * @param array $routes
     * @param array $serverVars
     */
    public function __construct(Router $router, array $routes = array(), array $serverVars = array())
    {
        $this->router = $router;
        $this->routes = $routes;
        $this->serverVars = $serverVars;
        $this->configureRouter();
    }

    /**
     * Configure the router with all the options that we have specified in our routes file.
     */
    protected function configureRouter()
    {
        $routes = $this->routes;
        if(isset($routes['route_groups'])) {
            $groups = $routes['route_groups'];
            unset($routes['route_groups']);
            foreach($groups as $prefix => $routeGroup) {
                $this->processRouteList($routeGroup, $prefix);
            }
        }

        $this->processRouteList($routes);
    }

    /**
     * Process an array of routes, and register them.
     *
     * @param array $routes
     * @param null $prefix
     */
    protected function processRouteList(array $routes, $prefix = null) {
        foreach($routes as $routeName => $route) {
            if($prefix) {
                $route['path'] = $prefix . '/' . $route['path'];
                // Sanity check
                $route['path'] = str_replace('//', '/', $route['path']);
            }
            $this->addRoute($routeName, $route);
        }
    }

    /**
     * Register a single route with the router.
     *
     * @param $routeName
     * @param array $route
     */
    protected function addRoute($routeName, array $route) {
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

    /**
     * Determine if the path in $_SERVER matches a registered route.
     * @return \Aura\Router\Route|bool
     */
    public function determineRouting()
    {
        $serverVars = $this->serverVars;

        if (!isset($serverVars['REQUEST_URI'])) {
            return false;
        }

        $path = parse_url($serverVars['REQUEST_URI'], PHP_URL_PATH);
        $this->lastRoute = $path;
        $result = $this->router->match($path, $serverVars);
        return $result;
    }

    /**
     * @return null|string
     */
    public function getLastRoute()
    {
        return $this->lastRoute;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    public function getRouteForName($name, array $args = array()) {
        return $this->router->generate($name, $args);
    }
}
