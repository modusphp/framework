<?php

namespace Modus\Template\Helper;

use Aura\Html\Helper\AbstractHelper;
use Modus\Route\Manager;

class LinkGenerator extends AbstractHelper
{

    protected $routes;
    protected $router;
    protected $lastRoute;

    public function __construct(Manager $router)
    {
        $this->router = $router;
    }

    public function __invoke($routeName, array $arguments = [])
    {
        return $this->router->generatePath($routeName, $arguments);
    }
}
