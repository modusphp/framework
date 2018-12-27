<?php

namespace Modus\Auth\Router;

use Aura\Router\Route;
use Modus\Auth\Service;

class StandardAuthDriver implements RouterAuthInterface
{
    /**
     * @var Route
     */
    protected $redirectRoute;

    /**
     * @var Service
     */
    protected $authService;

    public function __construct(Service $authService, Route $redirectRoute)
    {
        $this->authService = $authService;
        $this->redirectRoute = $redirectRoute;
    }

    public function checkAuth(Route $requestedRoute) : Route
    {
        $auth = $this->authService->resume();
        if (!$auth->isValid()) {
            return $this->redirectRoute;
        }

        return $requestedRoute;
    }
}
