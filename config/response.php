<?php

$di->params['Aura\Http\Manager'] = [
    'message_factory' => $di->lazyNew('Aura\Http\Message\Factory'),
    'transport' => $di->lazyNew('Aura\Http\Transport')
];

$di->params['Aura\Http\Transport'] = [
    'phpfunc' => $di->lazyNew('Aura\Http\PhpFunc'),
    'options' => $di->lazyNew('Aura\Http\Transport\Options'),
    'adapter' => $di->lazyNew('Aura\Http\Adapter\Curl'),
];

$di->params['Aura\Http\Adapter\Curl'] = [
    'stack_builder' => $di->lazyNew('Aura\Http\Message\Response\StackBuilder')
];

$di->params['Aura\Http\Message\Response\StackBuilder'] = [
    'message_factory' => $di->lazyNew('Aura\Http\Message\Factory'),
];