<?php

namespace Modus\Responder;

use Aura\Html\HelperLocator;
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
     * @param Response $response
     * @param View\View $template
     * @param Accept\Accept $contentNegotiation
     * @throws Exception\ContentTypeNotValidException
     */
    public function __construct(
        Response $response,
        View\View $template,
        Accept\Accept $contentNegotiation,
        HelperLocator $locator
    )
    {
        $this->response = $response;
        $this->template = $template;
        $this->contentNegotiation = $contentNegotiation;
        $this->locator = $locator;

        $this->determineResponseType();
    }

    /**
     * Processes the results of the Action.
     *
     * @param array $results
     * @return $this
     */
    abstract public function process(array $results);

    /**
     * Prepares and sends the HTTP response. Directly outputs to the browser with print.
     */
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
        header('Connection: close');

        // send content
        print($response->content->get());

    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return View\View
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set the response content.
     *
     * @param $content
     */
    public function setContent($content)
    {
        $this->response->content->set($content);
    }

    /**
     * Set a header.
     *
     * @param $key
     * @param $value
     */
    public function setHeader($key, $value)
    {
        $this->response->headers->set($key, $value);
    }

    /**
     *
     * Sets a cookie value in the response.
     *
     * @param string $name The name of the cookie.
     *
     * @param string $value The value of the cookie.
     *
     * @param int|string $expire The Unix timestamp after which the cookie
     * expires.  If non-numeric, the method uses strtotime() on the value.
     *
     * @param string $path The path on the server in which the cookie will be
     * available on.
     *
     * @param string $domain The domain that the cookie is available on.
     *
     * @param bool $secure Indicates that the cookie should only be
     * transmitted over a secure HTTPS connection.
     *
     * @param bool $httponly When true, the cookie will be made accessible
     * only through the HTTP protocol. This means that the cookie won't be
     * accessible by scripting languages, such as JavaScript.
     *
     * @return null
     *
     */
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

    /**
     * Sets a status code.
     *
     * @param $code The numeric statis code (e.g. 404)
     * @param null|string $phrase The status code phrase (e.g. Not Found)
     * @param null|string $version The HTTP version.
     */
    public function setStatus($code, $phrase = null, $version = null)
    {
        $this->response->status->set($code, $phrase, $version);
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
