<?php

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Monolog\Logger;
use Monolog\Handler\NullHandler;

class BootstrapTest extends MockeryTestCase {

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
    protected $router;

    /**
     * @var \Modus\Application\Bootstrap
     */
    protected $bootstrap;

    /**
     * @var \Modus\Response\ResponseManager $responseManager
     */
    protected $responseManager;

    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $serverRequest;

    protected function setUp() {
        $this->responder = Mockery::mock('Modus\Response\Interfaces\ResponseGenerator');
        $this->action = Mockery::mock('stdClass');
        $this->container = Mockery::mock('Aura\Di\Container');
        $this->config = Mockery::mock('Modus\Config\Config');
        $this->router = new \Modus\Route\Manager(new \Aura\Router\RouterContainer());
        $this->serverRequest = new \Zend\Diactoros\ServerRequest([], [], 'http://www.brandonsavage.net/');

        $route = \Modus\Route\Manager::route('example', '/')->extras(['action' => 'A\B\C', 'responder' => 'D\E\F']);
        $this->router->loadRoutes([$route]);

        $this->responseManager = Mockery::mock('Modus\Response\ResponseManager');

        $this->bootstrap = new \Modus\Application\Bootstrap(
            $this->config,
            $this->container,
            $this->router,
            $this->serverRequest,
            $this->getErrorHandler(),
            $this->responseManager
        );
    }

    public function testRouteRoutedAndLoaded() {
        $this->responseManager->shouldReceive('process')->once();


        $this->action->shouldReceive('__invoke')->once()->with()->andReturn(new \Aura\Payload\Payload());

        $this->container->shouldReceive('newInstance')->once()->with('D\E\F')->andReturn($this->responder);
        $this->container->shouldReceive('newInstance')->once()->with('A\B\C')->andReturn($this->action);

        $this->config->shouldReceive('getContainer')->andReturn($this->container);
        $this->config->shouldReceive('getConfig')->andReturn([]);

        $this->bootstrap->execute();
    }

    public function testNullResultCreatesAutomaticPayload() {
        $this->responseManager->shouldReceive('process')->once();


        $this->action->shouldReceive('__invoke')->once()->with()->andReturn(null);

        $this->container->shouldReceive('newInstance')->once()->with('D\E\F')->andReturn($this->responder);
        $this->container->shouldReceive('newInstance')->once()->with('A\B\C')->andReturn($this->action);

        $this->config->shouldReceive('getContainer')->andReturn($this->container);
        $this->config->shouldReceive('getConfig')->andReturn([]);

        $this->bootstrap->execute();
    }

    public function testNotFound4040Error() {
        $this->config->shouldReceive('getConfig')->andReturn(['error_page' => ['404' => 'NotFound\Error404']]);

        $this->container->shouldReceive('newInstance')->once()->with('NotFound\Error404')->andReturn($this->responder);
        $this->responseManager->shouldReceive('process')->once();

        $serverRequest = new \Zend\Diactoros\ServerRequest([], [], 'http://www.brandonsavage.net/abc');

        $bootstrap = new \Modus\Application\Bootstrap(
            $this->config,
            $this->container,
            $this->router,
            $serverRequest,
            $this->getErrorHandler(),
            $this->responseManager
        );

        $bootstrap->execute();
    }

    /**
     * @expectedException Modus\Common\Route\Exception\NotFoundException
     */
    public function testNotFoundNoErrorPageThrowsException() {
        $this->config->shouldReceive('getConfig')->andReturn([]);

        $serverRequest = new \Zend\Diactoros\ServerRequest([], [], 'http://www.brandonsavage.net/abc');

        $bootstrap = new \Modus\Application\Bootstrap(
            $this->config,
            $this->container,
            $this->router,
            $serverRequest,
            $this->getErrorHandler(),
            $this->responseManager
        );

        $bootstrap->execute();
    }

    protected function getErrorHandler() {
        $runner = Mockery::mock('League\BooBoo\BooBoo');
        $accept = Mockery::mock('Aura\Accept\Accept');

        $loggers = [
            'error' => new Logger('error', [new NullHandler()]),
            'event' => new Logger('event', [new NullHandler()]),
        ];

        $handler = new Modus\ErrorLogging\Manager($runner, $accept, $loggers);
        return $handler;
    }
}
