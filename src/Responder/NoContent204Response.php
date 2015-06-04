<?php

namespace Modus\Responder;

use Aura\Payload_Interface\PayloadInterface;

class NoContent204Response extends Web
{

    public function process(PayloadInterface $payload = null)
    {
        $this->setStatus(204, 'No Content');
    }
}
