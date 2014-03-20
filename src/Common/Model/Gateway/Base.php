<?php

namespace Modus\Common\Model\Gateway;

use Modus\Common\Model\Storage;

abstract class Base {

    protected $storage;

    public function __construct(Storage\Database $storage) {
        $this->storage = $storage;
    }
}