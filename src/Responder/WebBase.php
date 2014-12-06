<?php

namespace Modus\Responder;

use Aura\Web;
use Aura\View;

abstract class WebBase
{

    /**
     * @var Web\Response
     */
    protected $response;

    /**
     * @var View\View
     */
    protected $template;

    public function __construct(Web\Response $response, View\View $template)
    {
        $this->response = $response;
        $this->template = $template;
    }

    public function sendResponse()
    {
        $response = $this->response;
        header($response->status->get(), true, $response->status->getCode());

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
}
