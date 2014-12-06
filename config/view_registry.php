<?php

$layout = $config['template']['layout'];
$views = $config['template']['views'];

return [
    'layout' => [
        'layout' => $layout . 'layout.php',
    ],

    'views' => [
        'test' => $views . 'test.php',
        '404' => $views . '404.php',
        '500' => $views . '500.php',
    ]
];