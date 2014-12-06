<?php

$registry = require ('view_registry.php');

$di->params['Aura\View\View'] = [
    'view_registry' => $di->lazyNew('Aura\View\TemplateRegistry', ['map' => $registry['views']]),
    'layout_registry' => $di->lazyNew('Aura\View\TemplateRegistry', ['map' => $registry['layout']]),
    'helpers' => $di->lazyNew('Aura\Html\HelperLocator'),
];