<?php

require_once('../vendor/autoload.php');

$builder = new Aura\Di\ContainerBuilder();
$config = new Modus\Config\Config($_SERVER['MY_ENV'], realpath('../config'), $builder);


$di = $config->getContainer();
$framework = $di->newInstance('Modus\Application\Bootstrap');
$framework->execute();
