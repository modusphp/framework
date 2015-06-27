<?php

namespace Modus\Response\Interfaces;

use Aura\Payload_Interface\PayloadInterface;

interface HtmlGenerator extends ResponseGenerator
{
    public function generateHtml(PayloadInterface $payload);
}
