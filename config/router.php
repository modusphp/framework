<?php

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

$di->params['Modus\Router\Standard'] = [
    'router' => $di->lazyNew('Aura\Router\Router'),
    'routes' => require('routes.php'),
];

$di->params['Modus\Template\Helper\LinkGenerator'] = [
    'standardRouter' => $di->lazyNew('Modus\Router\Standard'),
];