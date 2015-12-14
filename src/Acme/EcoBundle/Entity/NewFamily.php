<?php
namespace Acme\EcoBundle\Entity;

class NewFamily
{
    protected $familyName;

    public function setFamilyName($familyName)
    {
        $this->familyName = $familyName;

        return $this;
    }

    public function getFamilyName()
    {
        return $this->familyName;
    }
}