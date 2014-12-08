<?php

namespace AppConfig;

use Aura\Di\Config;
use Aura\Di\Container;

class Database extends Config
{

    public function define(Container $di)
    {
        $config = $di->get('config')->getConfig();

        $database = $config['database'];
        $type = $database['type'];
        $dbname = $database['dbname'];

        $databaseConnections = [];

        $dsn = '%s:host=%s;dbname=%s';

        $defaultDsn = sprintf($dsn, $type, $database['default']['host'], $dbname);

        $databaseConnections['default'] = function () use ($di, $defaultDsn, $type, $database) {
            return $di->newInstance('\Aura\Sql\ExtendedPdo', [
                    'dsn' => $defaultDsn,
                    'username' => $database['default']['user'],
                    'password' => $database['default']['pass'],
                ]
            );
        };
        /*
         * --------------------------------------------------
         * Configure the WRITE databases
         * --------------------------------------------------
         */
        foreach ($database['write'] as $key => $dbconfig) {
            $databaseConnections['write'][$key] =
                $di->lazyNew('\Aura\Sql\ExtendedPdo', [
                        'dsn' => sprintf(
                            $dsn,
                            $type,
                            $dbconfig['host'],
                            $dbname
                        ),
                        'username' => $dbconfig['user'],
                        'password' => $dbconfig['pass'],
                    ]
                );
        }

        /*
         * --------------------------------------------------
         * Configure the READ databases
         * --------------------------------------------------
         */
        foreach ($database['read'] as $key => $dbconfig) {
            $databaseConnections['read'][$key] =
                $di->lazyNew('\Aura\Sql\ExtendedPdo', [
                        'dsn' => sprintf(
                            $dsn,
                            $type,
                            $dbconfig['host'],
                            $dbname
                        ),
                        'username' => $dbconfig['user'],
                        'password' => $dbconfig['pass'],
                    ]
                );
        }

        /*
         * --------------------------------------------------
         * Database Configuration
         * --------------------------------------------------
         */
        $di->params['Aura\Sql\ConnectionLocator'] = [
            'default' => $databaseConnections['default'],
            'read' => $databaseConnections['write'],
            'write' => $databaseConnections['read'],
        ];

        $di->params['Aura\SqlQuery\QueryFactory'] = [
            'db' => $type,
        ];
    }
}