<?php

namespace Modus\Route;

use Aura\Router\Route;
use Aura\Router\RouterContainer;
use Psr\Http\Message\ServerRequestInterface;

class Manager
{
    /**
     * @var RouterContainer
     */
    protected $container;

    public function __construct(RouterContainer $container)
    {
        $this->container = $container;
    }

    public static function route($name, $path)
    {
        $route = new Route();
        $route->name($name)->path($path);
        return $route;
    }

    public function loadRoutes(array $routes)
    {
        $map = $this->container->getMap();
        foreach ($routes as $route) {
            $map->addRoute($route);
        }

        return $this->container;
    }

    public function matchRoute(ServerRequestInterface $serverRequest)
    {
        $matcher = $this->container->getMatcher();
        $route = $matcher->match($serverRequest);

        return $route;
    }

    public function generatePath($name, array $args = [])
    {
        $generator = $this->container->getGenerator();
        return $generator->generate($name, $args);
    }
}
