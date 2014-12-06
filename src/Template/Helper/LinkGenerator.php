<?php

namespace Modus\Template\Helper;

use Aura\Html\Helper\AbstractHelper;
use Modus\Router\Standard;

class LinkGenerator extends AbstractHelper
{

    protected $routes;
    protected $router;
    protected $lastRoute;

    public function __construct(Standard $standardRouter)
    {
        $this->router = $standardRouter->getRouter();
    }

    public function __invoke($routeName, array $arguments = array())
    {
        return $this->router->generate($routeName, $arguments);
    }
}
