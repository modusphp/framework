<?php

use Aura\Di\Container;
use Aura\Di\Factory;

$di = new Container(new Factory);

require ('session.php');
require ('router.php');
require ('error.php');
require ('models.php');
require ('responder.php');
require ('request_response.php');
require ('views.php');

/*
 * --------------------------------------------------
 * DI Parameter Configuration
 * --------------------------------------------------
 */
$di->params['Modus\Application\Bootstrap'] = array(
    'config' => $config,
    'di' => $di,
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