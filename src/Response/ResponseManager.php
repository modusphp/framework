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
     * @var Accept\Accept
     */
    protected $contentNegotiation;

    /**
     * @param Response $response
     * @param View\View $template
     * @param Accept\Accept $contentNegotiation
     * @param HelperLocator $locator
     * @throws Exception\ContentTypeNotValidException
     */
    public function __construct(
        Accept\Accept $contentNegotiation
    )
    {
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
        $typeMap = $generator->checkContentResponseType();

        $availableTypes = array_keys($typeMap);

        $type = $this->determineResponseType($availableTypes);

        $methodToCall = $typeMap[$type];
        $response = $generator->$methodToCall($payload);
        return $this->sendResponse($response);

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
            $this->contentType = $contentType;
            return $contentType;
        }

        throw new Exception\ContentTypeNotValidException('The content type requested was not a valid response type');
    }

    protected function useTemplateForContent()
    {
        $this->setContent($this->template->__invoke());
    }
}
