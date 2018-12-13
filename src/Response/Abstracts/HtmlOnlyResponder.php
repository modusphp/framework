<?php

namespace Modus\Response\Abstracts;

use Aura\Html\HelperLocator;
use Aura\View\View;
use Modus\Payload\PayloadInterface;
use Modus\Response\Interfaces\HtmlGenerator;
use Modus\Response\Response;

abstract class HtmlOnlyResponder implements HtmlGenerator
{
    protected $response;
    protected $template;
    protected $helpers;

    public function __construct(
        Response $response,
        View $template,
        HelperLocator $helpers
    ) {
        $this->response = $response;
        $this->template = $template;
        $this->helpers = $helpers;
    }

    public function checkContentResponseType()
    {
        return [
            'text/html' => 'generateHtml',
        ];
    }

    abstract public function generateHtml(PayloadInterface $payload);
}
