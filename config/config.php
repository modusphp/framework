<?php

$rootPath = realpath(__DIR__ . '/..');

return array(

    /*
     * --------------------------------------------------
     * Are we in production?
     * --------------------------------------------------
     */
    "environment" => $_SERVER['MY_ENV'],

    /*
     * --------------------------------------------------
     * Default Root Path
     * --------------------------------------------------
     */
    "root_path" => $rootPath,

    /*
     * --------------------------------------------------
     * Database Configuration
     * --------------------------------------------------
     */
    'database' => [
        'type' => 'mysql',
        'dbname' => '',
        'default' => [
            "user" => "",
            "pass" => "",
            "host" => "127.0.0.1",
        ],

        'write' => [
            'master' => [
                "user" => "",
                "pass" => "",
                "host" => "127.0.0.1",
            ],
        ],

        'read' => [
            'slave' => [
                "user" => "",
                "pass" => "",
                "host" => "127.0.0.1",
            ],
        ]
    ],

    /*
     * --------------------------------------------------
     * Error statuses and the responders to use.
     * --------------------------------------------------
     */
    'error_page' => [
        '404' => null, // FQ Namespace
    ],

    /*
     * --------------------------------------------------
     * Template Directory and Layout
     * --------------------------------------------------
     */
    "template" => [
        "layout" => "$rootPath/views/",
        "views" => "$rootPath/views/",
    ],

    /*
     * --------------------------------------------------
     * Default Session Configuration
     * --------------------------------------------------
     */
    'default_session_segment' => 'modus',

    /*
      * --------------------------------------------------
      * Default Error Configuration
      * --------------------------------------------------
      */
    'error_logging' => [
        'error' => [
            'Monolog\Handler\StreamHandler' => [$rootPath . '/logs/error.log'],
        ],
        'event' => [
            'Monolog\Handler\StreamHandler' => [$rootPath . '/logs/event.log'],
        ],
    ],

    'use_booboo' => false,
);