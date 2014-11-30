<?php

namespace Modus\Template\Helper;

use Aura\Html\Helper\AbstractHelper;
use Modus\Session;


class Messages extends AbstractHelper {

    protected $segment;

    public function __construct(Session\Aura $session) {
        $this->segment = $session->getSegment();
    }

    public function __invoke() {
       $messages = $this->getErrors();
       $messages .= $this->getMessages();
       return $messages;
    }

    protected function getErrors() {
        $message = $this->segment->getFlash('failure');
        if($message) {
            return '<div class="failure">' . $message . '</div>';
        }

    }

    protected function getMessages() {
        $message = $this->segment->getFlash('success');
        if($message) {
            return '<div class="success">' . $message . '</div>';
        }
    }
}