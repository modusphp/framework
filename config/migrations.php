
<?php

if(function_exists('xdebug_disable')) {
    xdebug_disable();
}

$rootPath = realpath(__DIR__ . '/..');

require($rootPath . '/vendor/autoload.php');

if(isset($_SERVER['PHINX_ENV'])) {
    $env = $_SERVER['PHINX_ENV'];
} else {
    $env = 'dev';
    trigger_error('Phinx migration environment not set', E_USER_WARNING);
}

$configuration = new Modus\Config\Config($env, $rootPath . '/config', new Aura\Di\ContainerBuilder());

$config = $configuration->getConfig();

return [

    "paths" => [
        "migrations" => "$rootPath/migrations"
    ],

    "environments" => [
        "default_migration_table" => "phinxlog",
        "default_database" => "default",
        "default" => [
            "adapter" => $config['database']['type'],
            "host" => $config['database']['default']['host'],
            "name" => $config['database']['dbname'],
            "user" => $config['database']['default']['user'],
            "pass" => $config['database']['default']['pass'],
            "port" => 3306
            ],
        ],
    ];
