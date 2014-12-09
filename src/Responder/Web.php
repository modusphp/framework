<?php

namespace Modus\Responder;

use Modus\Responder\Exception;

use Aura\Accept;
use Aura\Web\Response;
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
     * The negotiated types.
     *
     * @var string
     */
    protected $useType = null;

    public function __construct(Response $response, View\View $template, Accept\Accept $contentNegotiation)
    {
        $this->response = $response;
        $this->template = $template;
        $this->contentNegotiation = $contentNegotiation;

        $this->determineResponseType();
    }

    public function sendResponse()
    {
        $response = $this->response;

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

        // send content
        echo $response->content->get();
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    abstract public function process(array $results);

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

    public function setContent($content)
    {
        $this->response->content->set($content);
    }

    public function setHeader($key, $value)
    {
        $this->response->headers->set($key, $value);
    }

    public function setCookie(
        $name,
        $value,
        $expire = null,
        $path = null,
        $domain = null,
        $secure = null,
        $httponly = null
    ) {
        $this->response->cookies->set($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    public function setStatus($code, $phrase = null, $version = null)
    {
        $this->response->status->set($code, $phrase, $version);
    }
}
