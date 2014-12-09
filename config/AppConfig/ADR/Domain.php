<?php

namespace AppConfig\ADR;

use Aura\Di;

/**
 * Set up the setters and other parameters passed into actions.
 *
 * Class Action
 * @package AppConfig\ADR
 */
class Domain extends Di\Config
{

    public function define(Di\Container $di)
    {

        /**
         * This is the basic configuration for the base database model.
         */
        $di->params['Modus\Common\Model\Storage\Database'] = array(
            'locator' => $di->lazyNew('Aura\Sql\ConnectionLocator'),
            'queryFactory' => $di->lazyNew('Aura\SqlQuery\QueryFactory'),
        );
        /**
         * Configure your model-specific arguments here.
         */
    }
}
