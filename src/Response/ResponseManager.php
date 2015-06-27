<?php

namespace Modus\Response;

use Aura\Payload_Interface\PayloadInterface;
use Modus\Responder\Exception;

use Aura\Accept;
use Modus\Response\Interfaces\ResponseGenerator;
use Modus\Response\Response;
use Aura\View;

class ResponseManager
{

    /**
     * @var Response
     */
    protected $response;

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
     * The negotiated types.
     *
     * @var string
     */
    protected $useType = null;

    /**
     * @var string The content type to use for the response.
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
    }

    /**
     * Processes the results of the Action.
     *
     * @var $payload PayloadInterface
     * @var $generator ResponseGenerator
     * @throws Exception\ContentTypeNotValidException
     */
    public function process(PayloadInterface $payload, ResponseGenerator $generator)
    {
        $typeValid = $generator->checkContentResponseType($this->contentType);

        $availableTypes = array_keys($typeValid);

        $type = $this->determineResponseType($availableTypes);

        if ($typeValid) {
            $response = $generator->$typeValid($payload);
            return $this->sendResponse($response);
        }

        throw new Exception\ContentTypeNotValidException('The content type requested was not a valid response type');
    }

    /**
     * Prepares and sends the HTTP response. Directly outputs to the browser with print.
     */
    public function sendResponse(Response $response)
    {
        $response = $response->getResponse();
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
     * Determines if the Accepts header types match what this responder is configured to return.
     * If not, we throw an exception.
     *
     * @throws Exception\ContentTypeNotValidException
     */
    protected function determineResponseType($availableTypes)
    {
        $bestType = $this->contentNegotiation->negotiateMedia($availableTypes);
        if ($bestType instanceof Accept\Media\MediaValue) {
            $contentType = $bestType->getValue();
            $subType = $bestType->getSubtype();
            return [$contentType, $subType];
        }

        throw new Exception\ContentTypeNotValidException('The content type requested was not a valid response type');
    }

    protected function useTemplateForContent()
    {
        $this->setContent($this->template->__invoke());
    }
}
