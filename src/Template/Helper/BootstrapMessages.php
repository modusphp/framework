<?php

namespace Modus\Template\Helper;

use Aura\Html\Helper\AbstractHelper;
use Modus\Session\Session;

class BootstrapMessages extends AbstractHelper
{

    protected $segment;

    protected $messageTypes = [
        'error' => 'alert-danger',
        'warning' => 'alert-warning',
        'info' => 'alert-info',
        'failure' => 'alert-danger',
        'success' => 'alert-success',
    ];

    public function __construct(Session $session)
    {
        $this->segment = $session->getSegment();
    }

    public function __invoke()
    {
        $messages = '';

        foreach ($this->messageTypes as $key => $type) {
            $message = $this->segment->getFlash($key);
            if ($message) {
                $messages .= sprintf('<div class="alert %s">%s</div>', $type, $message);
            }
        }

        return $messages;
    }
}
