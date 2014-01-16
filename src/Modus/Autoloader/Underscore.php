<?php

namespace Modus\Autoloader;

class Underscore extends Base {
    
    public function loader($class) {
        $class = str_replace('_', '/', $class);
        $class .= '.php';
        if(stream_resolve_include_path($class)) {
            include_once $class;
        }
    }
    
}