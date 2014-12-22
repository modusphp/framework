<?php

namespace Modus\Session;

use Aura\Session as AuraSession;

class Session
{

    public $instance;
    public $segment;

    public function __construct(AuraSession\Manager $manager, $defaultSegment = null)
    {
        $this->instance = $manager;
        $this->segment = $this->instance->newSegment($defaultSegment);
    }

    public function getInstance()
    {
        return $this->instance;
    }

    public function getSegment()
    {
        return $this->segment;
    }

    public function setSuccess($message)
    {
        $this->segment->setFlash('success', $message);
    }

    public function setFailure($message)
    {
        $this->segment->setFlash('failure', $message);
    }

    public function setInfo($message)
    {
        $this->segment->setFlash('info', $message);
    }

    public function setWarning($message)
    {
        $this->segment->setFlash('warning', $message);
    }

    public function setError($message)
    {
        $this->segment->setFlash('error', $message);
    }
}
