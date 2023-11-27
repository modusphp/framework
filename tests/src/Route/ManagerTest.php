<?php

namespace Tests\Modus\Route;

use Aura\Router\Route;
use Aura\Router\RouterContainer;
use Modus\Route\Manager;
use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\ServerRequestFactory;

class ManagerTest extends TestCase
{
    public function testRouteGeneratorCreatesARoute()
    {
        $route = Manager::route('test', '/a/b/c');
        $this->assertInstanceOf(Route::class, $route);
        $this->assertSame('test', $route->name);
        $this->assertSame('/a/b/c', $route->path);
    }

    public function testRoutesAreRegistered()
    {
        $container = new RouterContainer();
        $router = new Manager($container);
        $route = Manager::route('test', '/a/b/c');
        $route2 = Manager::route('test2', '/d/e/f');
        $router->loadRoutes([$route, $route2]);


        $routes = $container->getMap()->getRoutes();

        $this->assertCount(2, $routes);
        $this->assertSame($route, $container->getMap()->getRoute('test'));
    }

    public function testRoutesAreMatchedBasedOnParameters()
    {
        $serverRequest = new ServerRequest([], [], 'http://www.brandonsavage.net/a/b/c');
        $container = new RouterContainer();
        $router = new Manager($container);
        $route = Manager::route('test', '/a/b/c')->extras(['a' => 'b']);
        $route2 = Manager::route('test2', '/d/e/f');
        $router->loadRoutes([$route, $route2]);
        $matchedRoute = $router->matchRoute($serverRequest);

        // We can't assert same, because we clone the route object.
        $this->assertEquals($route->name, $matchedRoute->name);
    }
}