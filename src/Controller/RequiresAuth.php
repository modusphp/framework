<?php

namespace Modus\Controller;

abstract class RequiresAuth extends Base {

    protected function authRequired() {
        return [];
    }

    protected function preAction() {
        $badAuth = $this->authValid($this->action);
        if($badAuth) {
            throw new Exception\AuthRequired('Auth is required for ' . __CLASS__ . '::' . $this->action . '()');
        }
    }

    protected abstract function authValid($action);

}