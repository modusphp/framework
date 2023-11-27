<?php

namespace Modus\Responder;

use Aura\Payload_Interface\PayloadInterface;
use Modus\Response\Interfaces\HtmlGenerator;
use Laminas\Diactoros\Response\HtmlResponse;

class NoContent204Response implements HtmlGenerator
{

    public function generateHtml(PayloadInterface $payload)
    {
        $out = new HtmlResponse('');
        $out = $out->withStatus(204);
        return $out;
    }

    /**
     * This is a list of the content type return values in priority order. The
     * response manager will identify which content type the user requested,
     * using this list as a priority order for that determination.
     *
     * If no determination can be made, the first content type listed will be
     * preferred and used.
     *
     * The content types should be in key-value pairs of content-type => method,
     * where the method name is the method called for the content type.
     *
     * @return array
     */
    public function checkContentResponseType()
    {
        return [
            'text/html' => 'generateHtml',
        ];
    }
}
