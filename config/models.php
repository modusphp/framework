<?php
/*
 * --------------------------------------------------
 * Model Configuration
 * --------------------------------------------------
 */


$di->params['Modus\Common\Model\Storage\Database'] = array(
    'locator' => $di->lazyNew('Aura\Sql\ConnectionLocator'),
    'queryFactory' => $di->lazyNew('Aura\SqlQuery\QueryFactory'),
);