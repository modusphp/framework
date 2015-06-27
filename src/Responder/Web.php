<?php

namespace Modus\Responder;

use Aura\Html\HelperLocator;
use Aura\Payload_Interface\PayloadInterface;
use Modus\Responder\Exception;

use Aura\Accept;
use Modus\Response\Response;
use Aura\View;

abstract class Web
{

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var View\View
     */
    protected $template;

    /**
     * @var Accept\Accept
     */
    protected $contentNegotiation;

    /**
     * The content types we will accept. Override in base classes.
     *
     * @var array
     */
    protected $accept = [
        'text/html'
    ];

    /**
     * @var HelperLocator
     */
    protected $locator;

    /**
     * The negotiated types.
     *
     * @var string
     */
    protected $useType = null;

    /**
     * @var The content type to use for the response.
     */
    protected $contentType;

    /**
     * @param Response $response
     * @param View\View $template
     * @param Accept\Accept $contentNegotiation
     * @param HelperLocator $locator
     * @throws Exception\ContentTypeNotValidException
     */
    public function __construct(
        Response $response,
        Accept\Accept $contentNegotiation
    )
    {
        $this->response = $response;
        $this->contentNegotiation = $contentNegotiation;

        $this->determineResponseType();
    }

    /**
     * Processes the results of the Action.
     *
     * @param PayloadInterface $payload
     * @return $this
     */
    abstract public function process(PayloadInterface $payload);

    /**
     * Prepares and sends the HTTP response. Directly outputs to the browser with print.
     */
    public function sendResponse()
    {
        $response = $this->response->getResponse();

        header($response->status->get(), true, $response->status->getCode());

        $response->content->setType($this->contentType);

        // send non-cookie headers
        foreach ($response->headers->get() as $label => $value) {
            header("{$label}: {$value}");
        }

        // send cookies
        foreach ($response->cookies->get() as $name => $cookie) {
            setcookie(
                $name,
                $cookie['value'],
                $cookie['expire'],
                $cookie['path'],
                $cookie['domain'],
                $cookie['secure'],
                $cookie['httponly']
            );
        }
        header('Connection: close');

        // send content
        print($response->content->get());
    }

    /**
     * @return View\View
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Determines if the Accepts header types match what this responder is configured to return.
     * If not, we throw an exception.
     *
     * @throws Exception\ContentTypeNotValidException
     */
    protected function determineResponseType()
    {
        $bestType = $this->contentNegotiation->negotiateMedia($this->accept);
        if ($bestType instanceof Accept\Media\MediaValue) {
            $this->contentType = $bestType->getValue();
            $this->useType = $bestType->getSubtype();
            return;
        }

        throw new Exception\ContentTypeNotValidException('The content type requested was not a valid response type');
    }

    protected function useTemplateForContent()
    {
        $this->setContent($this->template->__invoke());
    }
}
