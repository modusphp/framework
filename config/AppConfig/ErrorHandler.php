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
            'loggers' => ['error' => $di->get('error'), 'event' => $di->get('event')],
            'availableFormatters' => $this->formatters,
        ];
    }

    protected function configureMonolog($config, Container $di) {
        $config = $config['error_logging'];
        $logger = $config['logger'];
        $logs = [];

        foreach($config['logs'] as $name => $logConfig) {
            // Each logger can have >1 handler, so let's iterate.
            foreach($logConfig['handlers'] as $handler => $params) {
                $newHandler = $di->newInstance($handler, $params);

                // IF we have a custom-defined formatter, use it.
                if (isset($logConfig['formatter']) && !empty($logConfig['formatter'])) {
                    $newHandler->setFormatter($di->newInstance($logConfig['formatter'], $logConfig['formatterArgs']));
                }
                $logs[$name][] = $newHandler;
            }
        }

        foreach($logs as $key => $log) {
            $di->set($key, $di->lazyNew($logger, ['name' => $key, 'handlers' => $log]));
        }
    }

    protected function configureBooBoo($config, Container $di) {

        // We want to use the log handler for BooBoo.
        $handlers = [$di->newInstance('Savage\BooBoo\Handler\LogHandler', ['logger' => $di->get('error')])];

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

        if(isset($config['error_page_formatter'])) {
            $di->setter['Savage\BooBoo\Runner']['setErrorPageFormatter'] =
                $di->lazyNew($config['error_page_formatter']);
        }

        if ($config['use_booboo']) {
            $di->setter['Modus\ErrorLogging\Manager']['registerErrorHandler'] = true;
        }

        if($config['silence_errors']) {
            $di->setter['Savage\BooBoo\Runner']['silenceAllErrors'] = true;
        }
    }
}
