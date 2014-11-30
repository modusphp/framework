<?php

require ('helpers.php');

$di->params['Aura\View\View'] = [
    'view_registry' => $di->lazyNew('Aura\View\TemplateRegistry'),
    'layout_registry' => $di->lazyNew('Aura\View\TemplateRegistry'),
    'helpers' => $di->lazyNew('Aura\Html\HelperLocator'),
];
