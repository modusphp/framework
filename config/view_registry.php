<?php

$layout = $config['template']['layout'];

return [
    'layout' => [
        'layout' => $layout . 'layout.php',
    ],

    'views' => [
        'test' => $layout . 'test.php',
    ]
];