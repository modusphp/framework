<?php

namespace Modus\Response\Interfaces;

interface ResponseGenerator
{
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
    public function checkContentResponseType();
}
