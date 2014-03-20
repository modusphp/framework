<?php

namespace Modus\Common\Model\Storage;

use Aura\Sql\Connection;

abstract class Database {

    protected $master;
    protected $slave;

    public function __construct(Connection\AbstractConnection $master, Connection\AbstractConnection $slave) {
        $this->master = $master;
        $this->slave = $slave;
    }

}