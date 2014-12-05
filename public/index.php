<?php

require_once('../vendor/autoload.php');

$di = new Aura\Di\Container(new Aura\Di\Factory);
$di->setAutoResolve(false);
$config = new Modus\Config\Config($_SERVER['MY_ENV'], realpath('../config'), $di);

$di->set('config', $config);


$framework = $di->newInstance('Modus\Application\Bootstrap');
$framework->execute();
