<?php

namespace Modus\Auth;

use Aura\Auth;
use Aura\Auth\Service as AuthService;
use Aura\Auth\Exception as AuthException;

class Service
{

    protected $error = [];

    public function __construct(
        AuthService\LoginService $loginService,
        AuthService\LogOutService $logoutService,
        AuthService\ResumeService $resumeService,
        Auth\Auth $userObj
    ) {
        $this->loginService = $loginService;
        $this->logoutService = $logoutService;
        $this->resumeService = $resumeService;
        $this->userObj = $userObj;
    }

    public function getUser()
    {
        return $this->userObj;
    }

    public function resume()
    {
        $this->resumeService->resume($this->userObj);
        return $this->getUser();
    }

    public function authenticate($user = null, $pass = null)
    {
        $this->loginService->login($this->userObj, ['username' => $user, 'password' => $pass]);
        return $this->getUser();
    }

    public function logOut()
    {
        $this->logoutService->logout($this->userObj);
        return $this->getUser();
    }
}
