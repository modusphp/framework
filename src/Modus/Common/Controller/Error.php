<?php

namespace Modus\Common\Controller;

use Modus\Controller;

class Error extends Controller\Base {

    protected function checkAuth($action) {
        return false; # auth not required.
    }

    public function error() {

        $template = $this->template;
        $template->setInnerView('error.php');

        $this->response->setContent($template->render());
        return $this->response;
    }

}