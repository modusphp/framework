<?php

$loggers = [];
$handlers = [];

foreach($config['error_logging'] as $log_name => $log_handler) {
    foreach($log_handler as $handler => $params) {
        $loggers[$log_name][] = $di->newInstance($handler, $params);
    }
}

if($config['production']) {
    $handlers[] = $di->newFactory('Modus\ErrorLogging\TemplateHandler', ['template' => $di->lazyNew('Aura\View\TwoStep'), 'view' => $config['template']['error_view']]);
} else {
        $handlers[] = $di->newFactory('Whoops\Handler\PrettyPageHandler');
}

$di->setter['Monolog\Handler\AbstractHandler']['setFormatter'] = $di->lazyNew('Monolog\Formatter\LineFormatter', ['format' => "%datetime% > %level_name% > %message% %context% %extra%\n", 'dateFormat' => 'c']);

$di->set('logger', $di->lazyNew('Monolog\Logger', ['name' => 'exception']));
$di->set('app_logger', $di->lazyNew('Monolog\Logger'), ['name' => 'application', 'handlers' => $loggers['application']]);
$di->set('event_logger', $di->lazyNew('Monolog\Logger'), ['name' => 'event', 'handlers' => $loggers['event']]);

$handlers[] = $di->newFactory('Modus\ErrorLogging\MonologHandler', ['logger' => $di->get('logger')]);

$di->params['Modus\ErrorLogging\Manager'] = [
    'logger' => $di->get('logger'),
    'whoops' => $di->lazyNew('Whoops\Run'),
    'loggers' => $loggers['exception'],
    'handlers' => $handlers,
];