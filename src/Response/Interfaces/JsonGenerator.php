<?php

namespace Modus\Response\Interfaces;

use Modus\Payload\PayloadInterface;

interface JsonGenerator extends ResponseGenerator
{
    public function generateJson(PayloadInterface $payload);
}
