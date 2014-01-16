<?php

namespace Modus\Response;

use Aura\Http\Adapter\Curl as AdapterCurl;
use Aura\Http\Adapter\Stream as AdapterStream;
use Aura\Http\Cookie\JarFactory as CookieJarFactory;
use Aura\Http\Exception;
use Aura\Http\Manager;
use Aura\Http\Message\Factory as MessageFactory;
use Aura\Http\Message\Response\StackBuilder;
use Aura\Http\Multipart\FormData;
use Aura\Http\Multipart\PartFactory;
use Aura\Http\PhpFunc;
use Aura\Http\Transport;
use Aura\Http\Transport\Options as TransportOptions;

class Factory {
    
    protected $response;
    
    public function __construct() {
        $this->response = new Manager(
            new MessageFactory,
            new Transport(
                new PhpFunc,
                new TransportOptions,
                new AdapterCurl(
                    new StackBuilder(new MessageFactory)
                )
            )
        );
    }
    
    public function getResponse() {
        return $this->response->newResponse();
    }
    
    public function send($response) {
        $this->response->send($response);
    }
    
}