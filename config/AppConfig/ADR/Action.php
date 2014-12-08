<?php

namespace AppConfig\ADR;

use Aura\Di;

/**
 * Set up the setters and other parameters passed into actions.
 *
 * Class Action
 * @package AppConfig\ADR
 */
class Action extends Di\Config {

    public function define(Di\Container $di) {
        $di->setter['Application\Controller\Index']['setAuth'] = $di->lazyNew('Modus\Auth\Service');
    }

}