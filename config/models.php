<?php
/*
 * --------------------------------------------------
 * Model Configuration
 * --------------------------------------------------
 */
$di->params['Modus\Common\Model\Factory'] = array(
    'map' => array(

    ),
);

$di->params['Modus\Common\Model\Storage\Database'] = array(
    'master' => $di->lazyGet('master'),
    'slave' => $di->lazyGet('slave'),
);