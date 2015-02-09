<?php

namespace Modus\Responder;

use Aura\Accept\Accept;
use Aura\Html\HelperLocator;
use Aura\Web\Response;
use Aura\View\View;
use Modus\Response\HttpResponse;

abstract class Http extends Web {

    /**
     * @var HelperLocator
     */
    protected $locator;

    public function __construct(
        Response $response,
        View $template,
        Accept $contentNegotiation,
        HelperLocator $locator,
        HttpResponse $httpResponse
    ) {
        $this->$httpResponse = $httpResponse;
        parent::__construct($response, $template, $contentNegotiation, $locator);
    }

    public function sendResponse()
    {
        $this->httpResponse->sendResponse($this->response);
    }
}
