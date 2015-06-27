<?php

namespace Modus\Response;

use Aura\Web;

class Response
{
    /**
     * @var Web\Response
     */
    protected $response;

    public function __construct(Web\Response $response = null)
    {
        $this->response = $response;
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
     * @param integer $code The numeric status code (e.g. 404)
     * @param null|string $phrase The status code phrase (e.g. Not Found)
     * @param null|string $version The HTTP version.
     */
    public function setStatus($code, $phrase = null, $version = null)
    {
        $this->response->status->set($code, $phrase, $version);
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
