<?php

namespace Modus\Session;

use Aura\Session as AuraSession;

class Session
{

    public $instance;
    public $segment;

    public function __construct(AuraSession\Manager $manager, $defaultSegment = null)
    {
        $this->instance = $manager;
        $this->segment = $this->instance->newSegment($defaultSegment);
    }

    public function getInstance()
    {
        return $this->instance;
    }

    public function getSegment()
    {
        return $this->segment;
    }
}
