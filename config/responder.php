<?php

$di->params['Modus\Responder\WebBase'] = [
    'response' => $di->lazyNew('Aura\Web\Response'),
    'template' => $di->lazyNew('Modus\Template\TwoStep'),
];