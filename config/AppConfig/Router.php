<?php

namespace AppConfig;

use Aura\Di\Config;
use Aura\Di\Container;

class Router extends Config {

    public function define(Container $di)
    {
        /**
         * Aura\Router\Map
         */
        $di->params['Aura\Router\Router'] = [
            'routes' => $di->lazyNew('Aura\Router\RouteCollection'),
            'generator' => $di->lazyNew('Aura\Router\Generator'),
        ];

        $di->params['Aura\Router\RouteCollection'] = [
            'route_factory' => $di->lazyNew('Aura\Router\RouteFactory'),
        ];

        $config = $di->get('config');
        $config = $config->getConfig();

        $di->params['Modus\Router\Standard'] = [
            'router' => $di->lazyNew('Aura\Router\Router'),
            'routes' => require($config['root_path'] . '/config/routes.php'),
            'serverVars' => $_SERVER,
        ];
    }

}