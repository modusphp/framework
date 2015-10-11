<?php

use Modus\Auth\Router\StandardAuth;

class StandardTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Mockery\MockInterface
     */
    protected $authService;

    protected function setUp()
    {
        $this->authService = Mockery::mock('Modus\Auth\Service');

        $this->service = new StandardAuth($this->authService);
        $this->service->setRedirectPath('test_path');
    }

    public function testCheckAuthReturnsNewPathWhenAuthFails()
    {
        $this->authService->shouldReceive('resume')->andReturn(new \Modus\Auth\Auth(Mockery::mock('Modus\Auth\Session\SegmentInterface')->shouldIgnoreMissing()));
        $result = $this->service->checkAuth(Mockery::mock('Aura\Router\Route'));
        $this->assertEquals('test_path', $result);
    }

    public function testCheckAuthReturnsRouteWhenAuthMatches()
    {
        $auth = Mockery::mock('Modus\Auth\Auth');
        $auth->shouldReceive('isValid')->andReturn(true);
        $this->authService->shouldReceive('resume')->andReturn($auth);
        $routeMock = Mockery::mock('Aura\Router\Route');
        $result = $this->service->checkAuth($routeMock);
        $this->assertTrue($routeMock == $result);
    }
}