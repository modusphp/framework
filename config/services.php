<?php

/*
 * --------------------------------------------------
 * DI Parameter Configuration
 * --------------------------------------------------
 */
$di->params['Modus\Application\Bootstrap'] = array(
    'config' => $di->lazyGet('config'),
    'authService' => $di->lazyNew('Modus\Auth\Service'),
    'request' => $di->lazyNew('Aura\Web\Request'),
    'router' => $di->lazyNew('Modus\Router\Standard'),
    'handler' => $di->lazyNew('Modus\ErrorLogging\Manager'),
);

$di->params['Aura\Web\WebFactory'] = array(
    'globals' => $GLOBALS,
);

/*
 * --------------------------------------------------
 * Simple DI Settings
 * --------------------------------------------------
 */
$di->set('session', $di->lazyNew('Modus\Session\Aura'));
$di->set('router', $di->lazyNew('Modus\Router\Standard'));

/*
 * --------------------------------------------------
 * Database Configuration
 * --------------------------------------------------
 */
$di->params['Aura\Sql\ConnectionLocator'] = [
    'default' => null,
    'read' => null,
    'write' => null,
];

$di->params['Aura\SqlQuery\QueryFactory'] = [
    'db' => $config['database']['master']['adapter']
];