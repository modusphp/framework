<?php

$layout = $config['template']['layout'];
$views = $config['template']['views'];

return [
    'layout' => [
        'layout' => $layout . 'layout.php',
    ],

    'views' => [
        'test' => $views . 'test.php',
    ]
];