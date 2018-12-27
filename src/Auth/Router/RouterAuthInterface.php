<?php

namespace Modus\Auth\Router;

use Aura\Router\Route;

interface RouterAuthInterface
{
    public function checkAuth(Route $requestedRoute) : Route;
}
