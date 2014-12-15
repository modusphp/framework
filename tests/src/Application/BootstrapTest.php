<?php

use Monolog\Logger;
use Monolog\Handler\NullHandler;

class BootstrapTest extends PHPUnit_Framework_TestCase {

    /**
     * @var \Mockery\MockInterface
     */
    protected $responder;

    /**
     * @var \Mockery\MockInterface
     */
    protected $action;

    /**
     * @var \Mockery\MockInterface
     */
    protected $container;

    /**
     * @var \Mockery\MockInterface
     */
    protected $config;

    /**
     * @var \Mockery\MockInterface
     */
    protected $authService;

    /**
     * @var \Mockery\MockInterface
     */
    protected $router;

    protected function setUp() {
        $this->responder = Mockery::mock('Modus\Responder\Base');
        $this->action = Mockery::mock('stdClass');
        $this->container = Mockery::mock('Aura\Di\Container');
        $this->config = Mockery::mock('Modus\Config\Config');
        $this->authService = Mockery::mock('Modus\Auth\Service');
        $this->router = Mockery::mock('Modus\Router\Standard');

        $this->authService->shouldReceive('resume')->once();
        $this->config->shouldReceive('getContainer')->once()->andReturn($this->container);
        $this->bootstrap = new \Modus\Application\Bootstrap($this->config, $this->router, $this->authService, $this->getErrorHandler());


    }

    public function testRouteRoutedAndLoaded() {
        $this->responder->shouldReceive('process')->once()->with(['a' => 'b']);
        $this->responder->shouldReceive('sendResponse')->once();


        $this->action->shouldReceive('index')->once()->with()->andReturn(['a' => 'b']);

        $this->container->shouldReceive('newInstance')->once()->with('D\E\F')->andReturn($this->responder);
        $this->container->shouldReceive('newInstance')->once()->with('A\B\C')->andReturn($this->action);

        $this->config->shouldReceive('getContainer')->andReturn($this->container);
        $this->config->shouldReceive('getConfig')->andReturn([]);

        $route = new stdClass();
        $route->params = [
            'action' => 'A\B\C',
            'responder' => 'D\E\F',
            'method' => 'index'
        ];
        $this->router->shouldReceive('determineRouting')->andReturn($route);

        $this->bootstrap->execute();

        try {
            $this->action->mockery_verify();
            $this->responder->mockery_verify();
            $this->container->mockery_verify();
            $this->router->mockery_verify();
            $this->authService->mockery_verify();
            $this->config->mockery_verify();
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testNullResultCausesEmptyResultsArrayToBeCreated() {
        $this->responder->shouldReceive('process')->once()->with([]);
        $this->responder->shouldReceive('sendResponse')->once();


        $this->action->shouldReceive('index')->once()->with()->andReturn(null);

        $this->container->shouldReceive('newInstance')->once()->with('D\E\F')->andReturn($this->responder);
        $this->container->shouldReceive('newInstance')->once()->with('A\B\C')->andReturn($this->action);

        $this->config->shouldReceive('getContainer')->andReturn($this->container);
        $this->config->shouldReceive('getConfig')->andReturn([]);

        $route = new stdClass();
        $route->params = [
            'action' => 'A\B\C',
            'responder' => 'D\E\F',
            'method' => 'index'
        ];
        $this->router->shouldReceive('determineRouting')->andReturn($route);

        $this->bootstrap->execute();

        try {
            $this->action->mockery_verify();
            $this->responder->mockery_verify();
            $this->container->mockery_verify();
            $this->router->mockery_verify();
            $this->authService->mockery_verify();
            $this->config->mockery_verify();
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testNotFound4040Error() {
        $this->router->shouldReceive('determineRouting')->once()->andReturn(false);
        $this->router->shouldIgnoreMissing();
        $this->config->shouldReceive('getConfig')->andReturn(['error_page' => ['404' => 'NotFound\Error404']]);

        $this->container->shouldReceive('newInstance')->once()->with('NotFound\Error404')->andReturn($this->responder);
        $this->responder->shouldReceive('process')->with([])->once();
        $this->responder->shouldReceive('sendResponse');

        $this->bootstrap->execute();

        try {
            $this->responder->mockery_verify();
            $this->container->mockery_verify();
            $this->router->mockery_verify();
            $this->authService->mockery_verify();
            $this->config->mockery_verify();
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @expectedException Modus\Common\Route\Exception\NotFoundException
     */
    public function testNotFoundNoErrorPageThrowsException() {
        $this->router->shouldReceive('determineRouting')->once()->andReturn(false);
        $this->router->shouldIgnoreMissing();
        $this->config->shouldReceive('getConfig')->andReturn([]);

        $this->container->shouldReceive('newInstance')->once()->with('NotFound\Error404')->andReturn($this->responder);
        $this->responder->shouldReceive('process')->with([])->once();
        $this->responder->shouldReceive('sendResponse');

        $this->bootstrap->execute();

        try {
            $this->responder->mockery_verify();
            $this->container->mockery_verify();
            $this->router->mockery_verify();
            $this->authService->mockery_verify();
            $this->config->mockery_verify();
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testNoContentType406Error() {
        $route = new stdClass;
        $route->params = [
            'action' => 'A\B\C',
            'responder' => 'D\E\F',
            'method' => 'index'
        ];
        $this->router->shouldReceive('determineRouting')->once()->andReturn($route);
        $this->router->shouldIgnoreMissing();
        $this->config->shouldReceive('getConfig')->andReturn(['error_page' => ['406' => 'NotFound\Error406']]);

        $this->container->shouldReceive('newInstance')->once()->with('D\E\F')->andThrow('Modus\Responder\Exception\ContentTypeNotValidException');
        $this->container->shouldReceive('newInstance')->once()->with('NotFound\Error406')->andReturn($this->responder);

        $this->responder->shouldReceive('process')->with([])->once();
        $this->responder->shouldReceive('sendResponse');

        $this->bootstrap->execute();

        try {
            $this->responder->mockery_verify();
            $this->container->mockery_verify();
            $this->router->mockery_verify();
            $this->authService->mockery_verify();
            $this->config->mockery_verify();
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @expectedException Modus\Responder\Exception\ContentTypeNotValidException
     */
    public function testInvalidContentExceptionIsRethrownIfNotHandled() {
        $this->responder->shouldReceive('process')->once()->with(['a' => 'b']);
        $this->responder->shouldReceive('sendResponse')->once();


        $this->action->shouldReceive('index')->once()->with()->andReturn(['a' => 'b']);

        $this->container->shouldReceive('newInstance')->once()->with('D\E\F')->andThrow('Modus\Responder\Exception\ContentTypeNotValidException');

        $this->config->shouldReceive('getContainer')->andReturn($this->container);
        $this->config->shouldReceive('getConfig')->andReturn([]);

        $route = new stdClass();
        $route->params = [
            'action' => 'A\B\C',
            'responder' => 'D\E\F',
            'method' => 'index'
        ];
        $this->router->shouldReceive('determineRouting')->andReturn($route);

        $this->bootstrap->execute();
    }

    public function testAbsentResponderReturns204Responder() {
        $this->responder->shouldReceive('process')->once()->with(['a' => 'b']);
        $this->responder->shouldReceive('sendResponse')->once();


        $this->action->shouldReceive('index')->once()->with()->andReturn(['a' => 'b']);

        $this->container->shouldReceive('newInstance')->once()->with('Modus\Responder\NoContent204Response')->andReturn($this->responder);
        $this->container->shouldReceive('newInstance')->once()->with('A\B\C')->andReturn($this->action);

        $this->config->shouldReceive('getContainer')->andReturn($this->container);
        $this->config->shouldReceive('getConfig')->andReturn([]);

        $route = new stdClass();
        $route->params = [
            'action' => 'A\B\C',
            'method' => 'index'
        ];
        $this->router->shouldReceive('determineRouting')->andReturn($route);

        $this->bootstrap->execute();

        try {
            $this->action->mockery_verify();
            $this->responder->mockery_verify();
            $this->container->mockery_verify();
            $this->router->mockery_verify();
            $this->authService->mockery_verify();
            $this->config->mockery_verify();
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    protected function getErrorHandler() {
        $runner = Mockery::mock('Savage\BooBoo\Runner');
        $accept = Mockery::mock('Aura\Accept\Accept');

        $loggers = [
            'error' => new Logger('error', [new NullHandler()]),
            'event' => new Logger('event', [new NullHandler()]),
        ];

        $handler = new Modus\ErrorLogging\Manager($runner, $accept, $loggers);
        return $handler;
    }

}