<?php

namespace Modus\Autoloader;

abstract class Base {
    
    public function __construct() {
        spl_autoload_register(array($this, 'loader'));
    }
    
    abstract public function loader($class);
    
}