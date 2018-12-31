<?php

namespace Modus\Auth;

use Aura\Router\Route;
use Modus\Auth\Driver\RouterAuthInterface;

class DriverManager
{
    /**
     * @var array
     */
    protected $drivers = [];

    /**
     * @var RouterAuthInterface
     */
    protected $default;

    /**
     * DriverManager constructor.
     * @param array $drivers
     * @param RouterAuthInterface|null $default
     */
    public function __construct(array $drivers, ?RouterAuthInterface $default)
    {
        foreach ($drivers as $driver) {
            $this->addDriver($driver);
        }

        $this->default = $default;
    }

    /**
     * @param RouterAuthInterface $driver
     */
    public function addDriver(RouterAuthInterface $driver)
    {
        $this->drivers[get_class($driver)] = $driver;
    }

    /**
     * @param Route $route
     * @return Route
     */
    public function checkAuth(Route $route) : Route
    {
        $auth = $route->auth;
        if (!$auth) {
            return $route;
        }

        if (!is_string($auth) && !is_bool($auth)) {
            throw new \InvalidArgumentException('Route auth must be a string');
        }

        if (isset($this->drivers[$auth])) {
            /** @var RouterAuthInterface $authDriver */
            $authDriver = $this->drivers[$auth];
            return $authDriver->checkAuth($route);
        }

        if (!is_null($this->default)) {
            return $this->default->checkAuth($route);
        }
    }
}