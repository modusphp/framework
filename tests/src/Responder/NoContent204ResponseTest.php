<?php

class NoContent204ResponseTest extends PHPUnit_Framework_TestCase {

    public function test204StatusCodeSetOnResponse() {
        $response =new Aura\Web\WebFactory($GLOBALS);
        $response = $response->newResponse();
        $view = Mockery::mock('Aura\View\View');

        $acceptFactory = new Aura\Accept\AcceptFactory();
        $accept = $acceptFactory->newInstance();

        $noContent = new Modus\Responder\NoContent204Response($response, $view, $accept, new \Aura\Html\HelperLocator());
        $noContent->process([]);

        $this->assertEquals('HTTP/1.1 204 No Content', $response->status->get());
    }

}