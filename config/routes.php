<?php

return array(
    "auth" => [
        "path" => "/",
        "args" => ["values" => ['action' => 'Application\Controller\Index', 'responder' => 'Application\Responder\Index', 'method' => 'index']]
    ],

    "auth2" => [
        "path" => "/a/b/c",
        "args" => ["values" => ['action' => 'Application\Controller\Index', 'responder' => 'Application\Responder\Index', 'method' => 'index']]
    ],
);