<?php

return array(
    "auth" => [
        "path" => "/modus/public/",
        "args" => ["values" => ['controller' => 'index', 'action' => 'index']]
    ],

    "login" => [
        "path" => "/user/authenticate/{:test}",
        "args" => ["values" => ['controller' => 'user', 'action' => 'authenticate'], 'params' => ['test' => '(\w+)']]
    ],

    "dashboard" => [
        "path" => "/dashboard",
        "args" => ["values" => ['controller' => 'dashboard', 'action' => 'index']]
    ],

    "logout" => [
        "path" => "/user/logout",
        "args" => ["values" => ['controller' => 'user', 'action' => 'logout']]
    ],
);