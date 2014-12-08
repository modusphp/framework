<?php

namespace AppConfig;

use Aura\Di;

/**
 * Set up the setters and other parameters passed into actions.
 *
 * Class Action
 * @package AppConfig\ADR
 */
class Session extends Di\Config
{
    public function define(Di\Container $di)
    {

        $config = $di->get('config')->getConfig();

        /**
         * Session Configuration
         */
        $di->set('session_manager', $di->lazyNew('Aura\Session\Manager'));

        /**
         * Aura\Session\CsrfTokenFactory
         */
        $di->params['Aura\Session\CsrfTokenFactory']['randval'] = $di->lazyNew('Aura\Session\Randval');

        /**
         * Aura\Session\Manager
         */
        $di->params['Aura\Session\Manager'] = [
            'segment_factory' => $di->lazyNew('Aura\Session\SegmentFactory'),
            'csrf_token_factory' => $di->lazyNew('Aura\Session\CsrfTokenFactory'),
            'cookies' => $_COOKIE,
        ];

        /**
         * Aura\Session\Randval
         */
        $di->params['Aura\Session\Randval']['phpfunc'] = $di->lazyNew('Aura\Session\Phpfunc');

        /**
         * Aura\Session\Segment
         */
        $di->params['Aura\Session\Segment'] = [
            'session' => $di->lazyGet('session_manager'),
        ];

        $di->params['Modus\Session\Aura'] = [
            'manager' => $di->lazyNew('Aura\Session\Manager'),
            'defaultSegment' => $config['default_session_segment'],
        ];
    }
}