<?php

$rootPath = realpath(__DIR__ . '/..');

return array(

    /*
     * --------------------------------------------------
     * Are we in production?
     * --------------------------------------------------
     */
    "environment" => $env,

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

    'aura_configs' => [
        'AppConfig\Bootstrap',
        'AppConfig\Router',
        'AppConfig\Database',
        'AppConfig\AuraWeb',
        'AppConfig\Auth',
        'AppConfig\HtmlHelpers',
        'AppConfig\Session',
        'AppConfig\ErrorHandler',
        'AppConfig\ADR\Action',
        'AppConfig\ADR\Responder',
        'AppConfig\ADR\Domain',
    ],


);