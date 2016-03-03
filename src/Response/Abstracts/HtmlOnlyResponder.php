<?php

namespace Modus\Response\Abstracts;

use Aura\View\View;
use Modus\Payload\PayloadInterface;
use Modus\Response\Interfaces\HtmlGenerator;
use Modus\Response\Response;

abstract class HtmlOnlyResponder implements HtmlGenerator
{
    public function __construct(
        Response $response,
        View $template
    ) {
        $this->response = $response;
        $this->template = $template;
    }

    public function checkContentResponseType()
    {
        return [
            'text/html' => 'generateHtml',
        ];
    }

    abstract public function generateHtml(PayloadInterface $payload);

}