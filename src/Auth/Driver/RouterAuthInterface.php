<?php

namespace Modus\Auth\Driver;

use Aura\Router\Route;

interface RouterAuthInterface
{
    public function checkAuth(Route $requestedRoute) : Route;
}
