<?php

namespace Modus\Common\Model\User;

if (!function_exists('password_has') || !function_exists('password_verify')) {
    require (realpath(__DIR__ . '/../../../../vendor/ircmaxell/password-compat/lib/') . '/password.php');
}

class User
{

    public $id;
    public $username;
    public $email;
    public $password;

    protected $changed = false;

    public function getEmail()
    {
        return $this->email;
    }

    public function verifyCredentials($password)
    {
        return password_verify($password, $this->password);
    }

    public function setNewPassword($password)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $this->password = $hash;
        $this->changed = true;
    }

    public function configure(array $values = array())
    {
        foreach ($values as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        return $this;
    }

    public function isChanged()
    {
        return $this->changed;
    }
}
