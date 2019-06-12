<?php

namespace Test\Modus\Auth;

use Aura\Router\Route;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Modus\Auth\Driver\RouterAuthInterface;
use Modus\Auth\Driver\StandardAuth;
use Modus\Auth\AuthDriver;
use Modus\Auth\Service;
use Modus\Route\Manager;

class DriverManagerTest extends MockeryTestCase
{
    /**
     * @var \Mockery
     */
    protected $default;

    /**
     * @var StandardAuth
     */
    protected $driver;

    /**
     * @var AuthDriver
     */
    protected $driverManager;

    /**
     * @var \Mockery
     */
    protected $authService;

    protected function setUp()
    {
        $this->authService = \Mockery::mock(Service::class);
        $redirect = Manager::route('redirect', '/redirect/to');

        $this->driver = new StandardAuth($this->authService, $redirect);

        $this->default = \Mockery::mock(RouterAuthInterface::class);
        $this->driverManager = new AuthDriver([$this->driver], $this->default);
    }

    public function testDefaultUsedWhenNoDriverMatches()
    {
        $this->default->shouldReceive('checkAuth')->once()->andReturn(new Route());

        $route = Manager::route('default', '/default/path')->auth('NoMatchFound');
        $this->driverManager->checkAuth($route);
    }

    public function testDriverIsTriggered()
    {
        $auth = \Mockery::mock(\stdClass::class);
        $auth->shouldReceive('isValid')->once()->andReturn(true);

        $this->authService->shouldReceive('resume')->once()->andReturn($auth);

        $route = Manager::route('standarddriver', '/standard/driver')->auth(StandardAuth::class);
        $route2 = $this->driverManager->checkAuth($route);
        $this->assertSame($route, $route2);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testBadDriverThrowsException()
    {
        $int = 123;
        $route = Manager::route('badroute', '/some/bad/path')->auth($int);
        $this->driverManager->checkAuth($route);
    }
}
