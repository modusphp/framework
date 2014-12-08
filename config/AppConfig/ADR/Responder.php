<?php

namespace AppConfig\ADR;

use Aura\Di;

/**
 * Set up the setters and other parameters passed into responders.
 *
 * Class Action
 * @package AppConfig\ADR
 */
class Responder extends Di\Config {

    public function define(Di\Container $di) {

        /**
         * Basic configuration for base responder.
         */
        $di->params['Modus\Responder\WebBase'] = [
            'response' => $di->lazyNew('Aura\Web\Response'),
            'template' => $di->lazyNew('Aura\View\View'),
        ];

        $config = $di->get('config')->getConfig();
        $registry = require($config['root_path'] . '/config/view_registry.php');

        $di->params['Aura\View\View'] = [
            'view_registry' => $di->lazyNew('Aura\View\TemplateRegistry', ['map' => $registry['views']]),
            'layout_registry' => $di->lazyNew('Aura\View\TemplateRegistry', ['map' => $registry['layout']]),
            'helpers' => $di->lazyNew('Aura\Html\HelperLocator'),
        ];
    }

}