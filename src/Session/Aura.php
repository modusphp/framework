<?php

namespace Modus\Session;

use Aura\Session;

class Aura {
    
    public $instance;
    public $segment;

    public function __construct(Session\Manager $manager, $defaultSegment = null) {
        $this->instance = $manager;
        $this->segment = $this->instance->newSegment($defaultSegment);
    }
    
    public function getInstance() {
        return $this->instance;
    }

    public function getSegment() {
        return $this->segment;
    }
    
}