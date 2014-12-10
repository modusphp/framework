<?php

namespace Modus\Responder;

interface ResponderInterface
{
    public function sendResponse();

    public function process(array $results);
}
