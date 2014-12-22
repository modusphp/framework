<?php

namespace Modus\Template\Helper;

use Aura\Html\Helper\AbstractHelper;
use Modus\Session\Session;

class Messages extends AbstractHelper
{

    protected $segment;

    protected $messageTypes = [
        'error',
        'warning',
        'info',
        'failure',
        'success'
    ];

    public function __construct(Session $session)
    {
        $this->segment = $session->getSegment();
    }

    public function __invoke()
    {
        $messages = '';

        foreach($this->messageTypes as $type)
        {
            $message = $this->segment->getFlash($type);
            if ($message)
            {
                $messages .= sprintf('<div class="%s">%s</div>', $type, $message);
            }
        }

        return $messages;
    }
}
