<?php

namespace Modus\Router;

use Aura\Router\Router;
use Modus\Auth\Router\RouterAuthInterface;

class RouteManager
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
     * @var array
     */
    protected $authStack = [];

    /**
     * @var array An array of paths that require auth
     */
    protected $requiresAuth = [];

    /**
     * @param Router $router
     * @param array $routes
     * @param array $serverVars
     * @param array $routeAuthServices
     */
    public function __construct(
        Router $router,
        array $routes = array(),
        array $serverVars = array(),
        array $routeAuthServices = array()
    ) {
        $this->router = $router;
        $this->routes = $routes;
        $this->serverVars = $serverVars;
        $this->configureRouter();

        foreach ($routeAuthServices as $key => $service) {
            $this->addRouteAuth($service, $key);
        }
    }

    public function addRouteAuth(RouterAuthInterface $auth, $name = 'default')
    {
        $this->authStack[$name] = $auth;
        if (isset($this->routes['metadata']['redirect_routes'][$name])) {
            $redirect = $this->routes['metadata']['redirect_routes'][$name];
            $path = $this->router->generate($redirect);
            $auth->setRedirectPath($path);
        }
    }

    /**
     * @param string $name
     * @return RouterAuthInterface
     * @throws \InvalidArgumentException
     */
    public function getRouteAuth($name = 'default')
    {
        if (isset($this->authStack[$name])) {
            return $this->authStack[$name];
        }

        throw new \InvalidArgumentException('The route auth you requested was not provided');
    }

    public function removeRouteAuth($name)
    {
        if (isset($this->authStack[$name])) {
            unset($this->authStack[$name]);
        }

        return $this;
    }


    /**
     * Configure the router with all the options that we have specified in our routes file.
     */
    protected function configureRouter()
    {
        $routes = $this->routes;

        if (isset($routes['route_groups'])) {
            $groups = $routes['route_groups'];
            foreach ($groups as $prefix => $routeGroup) {
                $this->processRouteList($routeGroup, $prefix);
            }
        }

        $this->processRouteList($routes['routes']);
    }

    /**
     * Process an array of routes, and register them.
     *
     * @param array $routes
     * @param null $prefix
     */
    protected function processRouteList(array $routes, $prefix = null)
    {
        foreach ($routes as $routeName => $route) {
            if ($prefix) {
                if ($route['path']) {
                    $route['path'] = $prefix . '/' . $route['path'];
                } else {
                    // No trailing slashes!
                    $route['path'] = $prefix;
                }
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
    protected function addRoute($routeName, array $route)
    {
        $params = [];
        $secure = null;
        $request = 'HEAD|GET|DELETE|OPTIONS|PATCH|POST|PUT';

        $path = $route['path'];

        $values = $route['values'];

        if (!isset($values['action'])) {
            $values['action'] = null;
        }

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
        $result = $router->add($routeName, $path)
            ->addValues($values)
            ->addTokens($params)
            ->setSecure($secure)
            ->addServer(['REQUEST_METHOD' => $request]);

        if (isset($route['authRequired']) && $route['authRequired']) {
            $validator = 'default';
            if (isset($route['authValidator'])) {
                $validator = $route['authValidator'];
            }

            $this->requiresAuth[$routeName] = $validator;
        }
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
        if (!$result) {
            return $result;
        }
        $authValidator = $this->requiresAuth($result->name);
        if ($authValidator) {
            $checker = $this->getRouteAuth($authValidator);
            $route = $checker->checkAuth($result);
            if ($route === $result) {
                return $result;
            }

            $result = $this->router->match($route, $serverVars);
            if (!$result) {
                throw new \LogicException('Both the route requested and the auth redirect route are invalid');
            }
        }

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

    /**
     * Returns a full route path.
     *
     * @param $name
     * @param array $args
     * @return false|string
     */
    public function getRouteForName($name, array $args = array())
    {
        return $this->router->generate($name, $args);
    }

    /**
     * @param $routePathName
     * @return bool
     */
    public function requiresAuth($routePathName)
    {
        if (isset($this->requiresAuth[$routePathName])) {
            return $this->requiresAuth[$routePathName];
        }

        return false;
    }
}
