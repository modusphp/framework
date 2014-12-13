<?php

namespace AppConfig;

use Aura\Di\Config;
use Aura\Di\Container;

class ErrorHandler extends Config
{

    protected $formatters = [];

    public function define(Container $di)
    {
        $config = $di->get('config')->getConfig();

        $this->configureMonolog($config, $di);
        $this->configureBooBoo($config, $di);


        $di->params['Modus\ErrorLogging\Manager'] = [
            'runner' => $di->lazyNew('Savage\BooBoo\Runner'),
            'accept' => $di->lazyNew('Aura\Accept\Accept'),
            'loggers' => ['error' => $di->get('logger'), 'event' => $di->get('event_logger')],
            'availableFormatters' => $this->formatters,
        ];
    }

    protected function configureMonolog($config, Container $di) {
        $loggers = [];

        // Load the error loggers
        foreach ($config['error_logging'] as $log_name => $log_handler) {
            foreach ($log_handler as $handler => $params) {
                $loggers[$log_name][] = $di->newInstance($handler, $params);
            }
        }

        // Set the formatter for all the handlers in Monolog.
        $di->setter['Monolog\Handler\AbstractHandler']['setFormatter'] =
            $di->lazyNew(
                'Monolog\Formatter\LineFormatter',
                [
                    'format' => "%datetime% > %level_name% > %message% %context% %extra%\n",
                    'dateFormat' => 'c'
                ]
            );

        // Set the error loggers we need.
        $di->set('logger', $di->lazyNew('Monolog\Logger', ['name' => 'error', 'handlers' => $loggers['error']]));
        $di->set('event_logger', $di->lazyNew('Monolog\Logger', ['name' => 'event', 'handlers' => $loggers['event']]));
    }

    protected function configureBooBoo($config, Container $di) {

        // We want to use the log handler for BooBoo.
        $handlers = [$di->newInstance('Savage\BooBoo\Handler\LogHandler', ['logger' => $di->get('logger')])];

        // Configure the BooBoo formatters.
        $this->formatters = [];
        foreach($config['formatter_accepts'] as $k => $formatter) {
            $this->formatters[$k] = $di->newInstance($formatter);
        }

        $default[] = $di->newInstance($config['default_formatter']);

        // Set up the BooBoo configuration parameters.
        $di->params['Savage\BooBoo\Runner'] = [
            'formatters' => $default,
            'handlers' => $handlers,
        ];

        if ($config['use_booboo']) {
            $di->setter['Modus\ErrorLogging\Manager']['registerErrorHandler'] = true;
        }

        if($config['silence_errors']) {
            $di->setter['Savage\BooBoo\Runner']['silenceAllErrors'] = true;
        }
    }
}
