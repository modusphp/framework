<?php

namespace Modus\Template\Helper;

use Aura\Auth\Auth;
use Aura\Html\Helper\AbstractHelper;

class AuthChecker extends AbstractHelper
{
    /**
     * @var Auth
     */
    protected $user;

    public function __construct(Auth $user)
    {
        $this->user = $user;
    }

    public function __invoke()
    {
        return $this->user->isValid();
    }
}
