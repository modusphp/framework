<?php

use Aura\Di;


$di = new Di\Container(new Di\Forge(new Di\Config));

require ('views.php');
require ('session.php');
require ('router.php');
require ('error.php');
require ('models.php');

/*
 * --------------------------------------------------
 * DI Parameter Configuration
 * --------------------------------------------------
 */
$di->params['Modus\Application\Bootstrap'] = array(
    'config' => $config,
    'di' => $di,
    'context' => $di->lazyNew('Aura\Web\Context'),
    'router' => $di->lazyNew('Modus\Router\Standard'),
    'responseMgr' => $di->lazyNew('Modus\Response\Manager\Factory'),
    'handler' => $di->lazyNew('Modus\ErrorLogging\Manager'),
);

$di->params['Aura\Web\Context'] = array(
    'globals' => $GLOBALS,
);

$di->params['Modus\Controller\Base'] = array(
    'template' => $di->lazyNew('Aura\View\TwoStep'),
    'session' => $di->lazyNew('Modus\Session\Aura'),
    'context' => $di->lazyNew('Aura\Web\Context'),
    'response' => $di->lazyNew('Aura\Web\Response'),
    'factory' => $di->lazyNew('Modus\Common\Model\Factory'),
    'eventlog' => $di->get('event_logger'),
    'applog' => $di->get('app_logger'),
);

/*
 * --------------------------------------------------
 * Simple DI Settings
 * --------------------------------------------------
 */
$di->set('session', $di->lazyNew('Modus\Session\Aura'));
$di->set('router', $di->lazyNew('Modus\Router\Standard'));
$di->set('connection_factory', $di->lazyNew('Aura\Sql\ConnectionFactory'));

/*
 * --------------------------------------------------
 * Database Configuration
 * --------------------------------------------------
 */
$di->set('master', function() use ($config, $di) {
    $params = $config['database']['master'];
    $factory = $di->get('connection_factory');
    return $factory->newInstance(
        $params['adapter'],
        ['host' => $params['host'], 'dbname' => $params['name']],
        $params['user'],
        $params['pass']
    );
});

$di->set('slave', function() use ($config, $di) {
    $params = $config['database']['slave'];
    $factory = $di->get('connection_factory');
    return $factory->newInstance(
        $params['adapter'],
        ['host' => $params['host'], 'dbname' => $params['name']],
        $params['user'],
        $params['pass']
    );
});