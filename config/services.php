<?php

use Aura\Di\Container;
use Aura\Di\Factory;

$di = new Container(new Factory);

require ('views.php');
require ('session.php');
require ('router.php');
require ('error.php');
require ('models.php');
require ('response.php');
require ('responder.php');
/*
 * --------------------------------------------------
 * DI Parameter Configuration
 * --------------------------------------------------
 */
$di->params['Modus\Application\Bootstrap'] = array(
    'config' => $config,
    'di' => $di,
    'context' => $di->lazyNew('Aura\Web\WebFactory'),
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