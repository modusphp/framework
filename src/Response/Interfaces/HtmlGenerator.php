<?php

namespace Modus\Response\Interfaces;

use Modus\Payload\PayloadInterface;

interface HtmlGenerator extends ResponseGenerator
{
    public function generateHtml(PayloadInterface $payload);
}
