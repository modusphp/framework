<?php

namespace Modus\Autoloader;

require_once 'Base.php';

class Psr0 extends Base {
    
    public function loader($class) {
        $className = ltrim($class, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        if(stream_resolve_include_path($fileName)) {
            require_once $fileName;
        }
    }
    
}