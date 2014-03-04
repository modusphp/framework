<?php

namespace Modus\Template\Helper;

use Aura\View\Helper\AbstractHelper;
use Aura\Session;


class Messages extends AbstractHelper {

    protected $segment;

    public function __construct(Session\Segment $segment) {
        $this->segment = $segment;
    }

    public function __invoke() {
       $messages = $this->getErrors();
       $messages .= $this->getMessages();
       return $messages;
    }

    protected function getErrors() {
        $message = $this->segment->getFlash('failure');
        return '<div class="failure">' . $message . '</div>';

    }

    protected function getMessages() {
        $message = $this->segment->getFlash('success');
        return '<div class="success">' . $message . '</div>';
    }
}