<?php

namespace Modus\Response\Abstracts;

use Aura\View\View;
use Modus\Payload\PayloadInterface;
use Modus\Response\Interfaces\JsonGenerator;
use Modus\Response\Response;

abstract class HtmlOnlyResponder implements JsonGenerator
{
    public function __construct(
        Response $response
    ) {
        $this->response = $response;
    }

    public function checkContentResponseType()
    {
        return [
            'text/html' => 'generateJson',
        ];
    }

    abstract public function generateJson(PayloadInterface $payload);
}
