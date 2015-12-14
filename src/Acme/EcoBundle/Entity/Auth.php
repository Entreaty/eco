<?php
namespace Acme\EcoBundle\Entity;

class Auth
{
    public $losb;

    protected $login;

    protected $password;

    protected $captcha;


    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setCaptcha($captcha)
    {
        $this->captcha = $captcha;

        return $this;
    }

    public function getCaptcha()
    {
        return $this->captcha;
    }
}