<?php

use Modus\Auth;
use Modus\Auth\Service as AuthService;
use Modus\Auth\Service;

class AuthServiceTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Mockery\MockInterface
     */
    protected $login;

    /**
     * @var Mockery\MockInterface
     */
    protected $logout;

    /**
     * @var Mockery\MockInterface
     */
    protected $resume;

    /**
     * @var Service
     */
    protected $service;

    protected function setUp() {

        $this->login = Mockery::mock('Modus\Auth\Service\LoginService');
        $this->logout = Mockery::mock('Modus\Auth\Service\LogoutService');
        $this->resume = Mockery::mock('Modus\Auth\Service\ResumeService');
        $auth = new Auth\Auth(Mockery::mock('Modus\Auth\Session\SegmentInterface'));
        $this->service = new Service($this->login, $this->logout, $this->resume, $auth);
    }

    public function testGetUserReturnsAuthObject() {
        $user = $this->service->getUser();
        $this->assertInstanceOf('Modus\Auth\Auth', $user);
    }

    public function testResumeCallsResumeAndReturnsUser() {
        $this->resume->shouldReceive('resume')->with($this->service->getUser())->once();

        $user = $this->service->resume();
        $this->assertInstanceOf('Modus\Auth\Auth', $user);
    }

    public function testAuthenticateCallsAuthenticateMethodAndReturnsUser() {
        $user = $this->service->getUser();
        $this->login->shouldReceive('login')->with($user, ['username' => 'abc', 'password' => "123"])->once();

        $user = $this->service->authenticate('abc', '123');
        $this->assertInstanceOf('Modus\Auth\Auth', $user);
    }

    public function testLogoutCallsLogoutService() {
        $user = $this->service->getUser();
        $this->logout->shouldReceive('logout')->with($user)->once();

        $user = $this->service->logout();
        $this->assertInstanceOf('Modus\Auth\Auth', $user);
    }

    public function testExceptionHandlerPopulatesErrorMessage()
    {
        $this->login->shouldReceive('login')->andThrow('Modus\Auth\Exception\MultipleMatches');

        $this->service->authenticate();

        $this->assertTrue($this->service->hasError());
        $this->assertEquals('There were multiple matches for your credentials in the database.', $this->service->getError(true));
        $this->assertEquals(['message', 'exception'], array_keys($this->service->getError()));
    }

}