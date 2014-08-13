<?php

$di->params['Modus\Responder\WebBase'] = [
    'factory' => $di->lazyNew('Aura\Web\WebFactory'),
    'template' => $di->lazyNew('Modus\Template\TwoStep'),
];