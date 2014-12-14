<?php

/**
 *
 * Example:
 *
 * "some_route" => [
 *          "path" => "/a/b/{c}{format}",
 *          "values" => [
 *          'action' => 'Application\Controller\Index',
 *          'responder' => 'Application\Responder\Index',
 *          'method' => 'index',
 *          "format" => 'html' // a default param value for format
 *      ],
 *      "params" => ['c' => '\d+', 'format' => '(\.[^/]+)?',],
 *      "secure" => false,
 *      "request" => "GET|POST"
 * ],
 **/
return array(

    "home" => [
        "path" => "/",
        "values" => [
            'action' => 'Application\Action\Index',
            'responder' => 'Application\Responder\Index',
            'method' => 'index',
        ],
    ],

    "route_groups" => [
    ]
);
