<?php
namespace Acme\EcoBundle\Entity;

class NewMember
{
    protected $surname;
    protected $name;
    protected $secondname;
    protected $password;
    protected $login;
    protected $familyId;

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    public function getSurname()
    {
        return $this->surname;
    }

    public function setSecondname($secondname)
    {
        $this->secondname = $secondname;

        return $this;
    }

    public function getSecondname()
    {
        return $this->secondname;
    }

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

    public function setFamilyId($familyId)
    {
        $this->familyId = $familyId;

        return $this;
    }

    public function getFamilyId()
    {
        return $this->familyId;
    }

    protected $dueDate;



    public function getDueDate()
    {
        return $this->dueDate;
    }
    public function setDueDate(\DateTime $dueDate = null)
    {
        $this->dueDate = $dueDate;
    }
}