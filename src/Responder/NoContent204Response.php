<?php

namespace Modus\Responder;

class NoContent204Response extends Web
{

    public function process(array $results = array())
    {
        $this->setStatus(204, 'No Content');
    }
}
