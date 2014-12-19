<?php

namespace Modus\Auth\Router;

use Aura\Router\Route;
use Modus\Auth\Service;

class Standard implements RouterAuthInterface
{
    /**
     * @var Route
     */
    protected $redirectRoute;

    /**
     * @var Service
     */
    protected $authService;

    public function __construct(Service $authService)
    {
        $this->authService = $authService;
    }

    public function setRedirectPath($path)
    {
        $this->redirectRoute = $path;
    }

    public function checkAuth(Route $requestedRoute)
    {
        $auth = $this->authService->resume();
        if (!$auth->isValid()) {
            return $this->redirectRoute;
        }

        return $requestedRoute;
    }
}
