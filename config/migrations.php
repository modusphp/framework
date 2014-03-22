
<?php

$rootPath = realpath(__DIR__ . '/..');

$config = require($rootPath . '/config/config.php');

return [

    "paths" => [
        "migrations" => "migrations"
    ],

    "environments" => [
        "default_migration_table" => "phinxlog",
        "default_database" => "dev",
        "dev" => [
            "adapter" => $config['database']['master']['adapter'],
            "host" => $config['database']['master']['host'],
            "name" => $config['database']['master']['name'],
            "user" => $config['database']['master']['user'],
            "pass" => $config['database']['master']['pass'],
            "port" => 3306
            ],
        ],
    ];
