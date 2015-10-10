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

    /**
     * @var \Modus\Application\Bootstrap
     */
    protected $bootstrap;

    /**
     * @var \Modus\Response\ResponseManager $responseManager
     */
    protected $responseManager;

    protected function setUp() {
        $this->responder = Mockery::mock('Modus\Response\Interfaces\ResponseGenerator');
        $this->action = Mockery::mock('stdClass');
        $this->container = Mockery::mock('Aura\Di\Container');
        $this->config = Mockery::mock('Modus\Config\Config');
        $this->authService = Mockery::mock('Modus\Auth\Service');
        $this->router = Mockery::mock('Modus\Router\RouteManager');

        $this->responseManager = Mockery::mock('Modus\Response\ResponseManager');

        $this->authService->shouldReceive('resume')->once();
        $this->bootstrap = new \Modus\Application\Bootstrap($this->config, $this->container, $this->router, $this->authService, $this->getErrorHandler(), $this->responseManager);
    }

    public function testRouteRoutedAndLoaded() {
        $this->responseManager->shouldReceive('process')->once();


        $this->action->shouldReceive('index')->once()->with()->andReturn(new \Aura\Payload\Payload());

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
    }

    public function testNullResultCreatesAutomaticPayload() {
        $this->responseManager->shouldReceive('process')->once();


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
    }

    public function testNotFound4040Error() {
        $this->router->shouldReceive('determineRouting')->once()->andReturn(false);
        $this->router->shouldIgnoreMissing();
        $this->config->shouldReceive('getConfig')->andReturn(['error_page' => ['404' => 'NotFound\Error404']]);

        $this->container->shouldReceive('newInstance')->once()->with('NotFound\Error404')->andReturn($this->responder);
        $this->responseManager->shouldReceive('process')->once();

        $this->bootstrap->execute();
    }

    /**
     * @expectedException Modus\Common\Route\Exception\NotFoundException
     */
    public function testNotFoundNoErrorPageThrowsException() {
        $this->router->shouldReceive('determineRouting')->once()->andReturn(false);
        $this->router->shouldIgnoreMissing();
        $this->config->shouldReceive('getConfig')->andReturn([]);

        $this->bootstrap->execute();
    }

    protected function getErrorHandler() {
        $runner = Mockery::mock('League\BooBoo\Runner');
        $accept = Mockery::mock('Aura\Accept\Accept');

        $loggers = [
            'error' => new Logger('error', [new NullHandler()]),
            'event' => new Logger('event', [new NullHandler()]),
        ];

        $handler = new Modus\ErrorLogging\Manager($runner, $accept, $loggers);
        return $handler;
    }
}
