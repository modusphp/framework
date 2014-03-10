<?php

return array(
    "auth" => [
        "path" => "/modus/public/",
        "args" => ["values" => ['controller' => 'index', 'action' => 'index']]
    ],

    "login" => [
        "path" => "/user/authenticate",
        "args" => ["values" => ['controller' => 'user', 'action' => 'authenticate']]
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