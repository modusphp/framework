<?php

namespace Modus\Response\Interfaces;

use Aura\Payload_Interface\PayloadInterface;

interface JsonGenerator extends ResponseGenerator
{
    public function generateJson(PayloadInterface $payload);
}
