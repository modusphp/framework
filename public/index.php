<?php

require_once('../vendor/autoload.php');

#$config = require_once('../config/config.php');
#require_once('../config/services.php');

$di = new Aura\Di\Container(new Aura\Di\Factory);
$di->setAutoResolve(false);
$config = new Modus\Config\Config($_SERVER['MY_ENV'], realpath('../config'), $di);

$di->set('config', $config);

var_dump($config->getConfig()); die;

$framework = $di->newInstance('Modus\Application\Bootstrap');
$framework->execute();

