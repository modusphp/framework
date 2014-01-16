<?php

namespace Modus\Request;

interface RequestInterface {

    public function get($key, $default = ‘’);

}