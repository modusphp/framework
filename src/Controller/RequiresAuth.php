<?php

namespace Modus\Controller;

abstract class RequiresAuth extends Base {

    protected function authRequired() {
        return [];
    }

    protected function preAction() {
        $authValid = $this->authValid($this->action);
        if(!$authValid) {
            throw new Exceptions\AuthRequired('Auth is required for ' . __CLASS__ . '::' . $this->action . '()');
        }
    }

    protected abstract function authValid($action);

}