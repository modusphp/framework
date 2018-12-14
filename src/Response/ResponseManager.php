<?php

namespace Modus\Response;

use Aura\Payload_Interface\PayloadInterface;
use Modus\Response\Exception;

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
     * @var string
     */
    protected $contentType;

    /**
     * @param  HttpResponse $responseType
     * @throws Exception\ContentTypeNotValidException
     */
    public function __construct(
        HttpResponse $httpResponse,
        Accept\Accept $contentNegotiation
    ) {
        $this->httpResponse = $httpResponse;
        $this->contentNegotiation = $contentNegotiation;
    }

    /**
     * Processes the results of the Action.
     *
     * @var    $payload PayloadInterface
     * @var    $generator ResponseGenerator
     * @throws Exception\ContentTypeNotValidException
     */
    public function process(PayloadInterface $payload, ResponseGenerator $generator)
    {
        $typeMap = $generator->checkContentResponseType();

        $availableTypes = array_keys($typeMap);

        $type = $this->determineResponseType($availableTypes);

        // If we don't get a valid type back, let's force one, per the HTTP 1.1 spec.
        if (!$type) {
            $type = array_shift($availableTypes);
        }

        $methodToCall = $typeMap[$type];
        $response = $generator->$methodToCall($payload);
        $this->sendResponse($response);
    }

    /**
     * Prepares and sends the HTTP response. Directly outputs to the browser with print.
     */
    public function sendResponse(Response $response)
    {
        $response = $response->getResponse();
        $this->httpResponse->sendResponse($response, $this->contentType);
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
    }

    protected function useTemplateForContent()
    {
        $this->setContent($this->template->__invoke());
    }
}
