<?php

namespace Modus\Session;

class Aura {
    
    public $instance;
    public $segment;

    public function __construct($defaultSegment) {
        $this->instance = $session =
        new \Aura\Session\Manager(
            new \Aura\Session\SegmentFactory,
            new \Aura\Session\CsrfTokenFactory(
                new \Aura\Session\Randval(
                    new \Aura\Session\Phpfunc
                )
            ),
            $_COOKIE
        );

        $this->segment = $this->instance->newSegment($defaultSegment);
    }
    
    public function getInstance() {
        return $this->instance;
    }

    public function getSegment() {
        return $this->segment;
    }
    
}