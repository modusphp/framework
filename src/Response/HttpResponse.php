<?php

namespace Modus\Response;

use Aura\Web\Response;

class HttpResponse
{
    public function sendResponse(Response $response)
    {
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
}