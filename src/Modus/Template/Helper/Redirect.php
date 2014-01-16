<?php

namespace Modus\Template\Helper;

use Aura\View\Helper\AbstractHelper;

class Redirect extends AbstractHelper {
    
    public function __invoke($url) {
        header("Location: $url");
    }
    
}