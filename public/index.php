<?php

require_once('../vendor/autoload.php');

$builder = new Aura\Di\ContainerBuilder();
$environment = (isset($_SERVER['MODUS_ENV'])) ? $_SERVER['MODUS_ENV'] : 'production';
$config = new Modus\Config\Config($environment, realpath('../config'), $builder);


$di = $config->getContainer();
$framework = $di->newInstance('Modus\Application\Bootstrap');
$framework->execute();
