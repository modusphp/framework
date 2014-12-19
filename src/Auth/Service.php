<?php

namespace Modus\Auth;

use Aura\Auth;
use Aura\Auth\Service as AuthService;
use Aura\Auth\Exception;

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
        try {
            $this->resumeService->resume($this->userObj);
        } catch (Exception $e) {
            $this->handleException($e);
        }
        return $this->getUser();
    }

    public function authenticate($user = null, $pass = null)
    {
        try {
            $this->loginService->login($this->userObj, ['username' => $user, 'password' => $pass]);
        } catch (Exception $e) {
            $this->handleException($e);
        }
        return $this->getUser();
    }

    public function logOut()
    {
        try {
            $this->logoutService->logout($this->userObj);
        } catch (Exception $e) {
            $this->handleException($e);
        }
        return $this->getUser();
    }

    protected function handleException(Exception $e)
    {
        switch (true) {
            case ($e instanceof Exception\AlgorithmNotAvailable):
                $this->setError('The algorithm specified was unavailable', $e->getMessage());
                break;

            case ($e instanceof Exception\BindFailed):
                $this->setError('LDAP Binding failed.', $e->getMessage());
                break;

            case ($e instanceof Exception\ConnectionFailed):
                $this->setError('The LDAP/IMAP connection failed.', $e->getMessage());
                break;

            case ($e instanceof Exception\FileNotFound):
                $this->setError('The password file could not be found.', $e->getMessage());
                break;

            case ($e instanceof Exception\FileNotReadable):
                $this->setError('The file specified could not be read.', $e->getMessage());
                break;

            case ($e instanceof Exception\MultipleMatches):
                $this->setError('There were multiple matches for your credentials in the database.');
                break;

            case ($e instanceof Exception\PasswordColumnNotSpecified):
                $this->setError('The password column was not specified in the database.');

            case ($e instanceof Exception\PasswordIncorrect):
                $this->setError('The password was incorrect.');
                break;

            case ($e instanceof Exception\PasswordMissing):
                $this->setError('The password was not provided.');
                break;

            case ($e instanceof Exception\UsernameColumnNotSpecified):
                $this->setError('The username column was not specified in the database.');
                break;

            case ($e instanceof Exception\UsernameMissing):
                $this->setError('The username was not provided.');
                break;

            case ($e instanceof Exception\UsernameNotFound):
                $this->setError('The username was not found.');
                break;

            default:
                throw $e;
                break;
        }
    }

    protected function setError($message, $exceptionMessage = null)
    {
        $this->error = [
            'message' => $message,
            'exception' => $exceptionMessage,
        ];
    }

    public function hasError()
    {
        return !empty($this->error);
    }

    public function getError($messageOnly = false)
    {
        if ($messageOnly && $this->hasError()) {
            return $this->error['message'];
        }

        return $this->error;
    }
}
