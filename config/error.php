<?php

$loggers = [];
$handlers = [];

foreach($config['error_logging'] as $log_name => $log_handler) {
    foreach($log_handler as $handler => $params) {
        $loggers[$log_name][] = $di->newInstance($handler, $params);
    }
}

$di->setter['Monolog\Handler\AbstractHandler']['setFormatter'] =
    $di->lazyNew(
        'Monolog\Formatter\LineFormatter',
        [
            'format' => "%datetime% > %level_name% > %message% %context% %extra%\n",
            'dateFormat' => 'c'
        ]
    );


$di->set('logger', $di->lazyNew('Monolog\Logger', ['name' => 'error', 'handlers' => $loggers['error']]));
$di->set('event_logger', $di->lazyNew('Monolog\Logger', ['name' => 'event', 'handlers' => $loggers['event']]));


switch ($config['environment']) {
    case 'production':
        $formatters[] = $di->newInstance('Savage\BooBoo\Formatter\NullFormatter');
        $di->setters['Savage\BooBoo\Runner']['setErrorPageFormatter'] = $di->lazyNew('Savage\BooBoo\Formatter\NullForamtter');
        $di->setters['Savage\BooBoo\Runner']['silenceAllErrors'] = true;
        $handlers[] = $di->newInstance('Savage\BooBoo\Handler\LogHandler', ['logger' => $di->lazyGet('logger')]);
        break;

    case 'staging':
    case 'dev':
    case 'testing':
        $formatters[] = $di->newInstance('Savage\BooBoo\Formatter\HtmlTableFormatter');
        $handlers[] = $di->newInstance('Savage\BooBoo\Handler\LogHandler', ['logger' => $di->lazyGet('logger')]);
        break;
}

$di->params['Savage\BooBoo\Runner'] = [
    'formatters' => $formatters,
    'handlers' => $handlers,
];


$di->params['Modus\ErrorLogging\Manager'] = [
    'runner' => $di->lazyNew('Savage\BooBoo\Runner'),
    'loggers' => ['error' => $di->get('logger'), 'event' => $di->get('event_logger')]
];
