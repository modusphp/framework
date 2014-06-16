<?php

/**
* Aura\Router\Map
*/
$di->params['Aura\Router\Map'] = [
'definition_factory' => $di->lazyNew('Aura\Router\DefinitionFactory'),
'route_factory' => $di->lazyNew('Aura\Router\RouteFactory'),
];

$di->params['Modus\Router\Standard'] = [
    'router' => $di->lazyNew('Aura\Router\Map'),
    'routes' => require('routes.php'),
];

$di->params['Modus\Template\Helper\LinkGenerator'] = [
    'router' => $di->lazyNew('Aura\Router\Map'),
    'routes' => require('routes.php'),
];