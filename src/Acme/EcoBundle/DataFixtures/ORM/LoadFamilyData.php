<?php

namespace Acme\EcoBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Acme\EcoBundle\Entity\Family;

class LoadFamilyData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $em)
    {

    }

    public function getOrder()
    {
        return 1;
    }
}