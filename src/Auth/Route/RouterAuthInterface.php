<?php

namespace Modus\Auth\Route;

use Aura\Router\Route;

interface RouterAuthInterface
{
    public function checkAuth(Route $requestedRoute) : Route;
}
