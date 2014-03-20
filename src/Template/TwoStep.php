<?php

namespace Modus\Template;

use Aura\View;

class TwoStep extends View\TwoStep {
    
    public function getRawTemplate() {
        return $this->template;
    }
    
}