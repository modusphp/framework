<?php

/**
 *
 * Example:

"some_route" => [
    "path" => "/a/b/{c}{format}",
    "values" => [
        'action' => 'Application\Controller\Index',
        'responder' => 'Application\Responder\Index',
        'method' => 'index',
        "auth_required" => true,
        "format" => 'html' // a default param value for format
    ],
    "params" => ['c' => '\d+', 'format' => '(\.[^/]+)?',],
    "secure" => false,
    "request" => "GET|POST"
],

**/
return array(

    "auth" => [
        "path" => "/",
        "values" => [
            'action' => 'Application\Controller\Index',
            'responder' => 'Application\Responder\Index',
            'method' => 'index',
            "auth_required" => true,
        ],
    ],

    "auth2" => [
        "path" => "/a/br/c",
        "values" => ['action' => 'Application\Controller\Index', 'responder' => 'Application\Responder\Index', 'method' => 'index']
    ],
);