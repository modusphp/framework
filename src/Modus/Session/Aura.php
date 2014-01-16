<?php

namespace Modus\Session;

class Aura {
    
    protected $_instance;
    
    public function __construct() {
        $this->_instance = $session = 
        new \Aura\Session\Manager(
            new \Aura\Session\SegmentFactory,
            new \Aura\Session\CsrfTokenFactory(
                new \Aura\Session\Randval(
                    new \Aura\Session\Phpfunc
                )
            ),
            $_COOKIE
        );
    }
    
    public function getInstance() {
        return $this->_instance;
    }
    
}