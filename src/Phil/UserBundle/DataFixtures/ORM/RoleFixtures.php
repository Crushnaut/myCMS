<?php
// src/Phil/UserBundle/DataFixtures/ORM/RoleFixtures.php

namespace Phil\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Phil\UserBundle\Entity\Role;
use Phil\UserBundle\Entity\User;

class RoleFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $role1 = new Role();
        $role1->setName('Basic User Role');
        $role1->setRole('ROLE_USER');
        $manager->persist($role1);

        $role2 = new Role();
        $role2->setName('Admin User Role');
        $role2->setRole('ROLE_ADMIN');
        $manager->persist($role2);

        $role3 = new Role();
        $role3->setName('Test Role');
        $role3->setRole('ROLE_TEST');
        $manager->persist($role3);

        $role4 = new Role();
        $role4->setName('Another Test Role');
        $role4->setRole('ROLE_TESTER');
        $manager->persist($role4);

        $manager->flush();

        $this->addReference('userrole', $role1);
        $this->addReference('adminrole', $role2);
        $this->addReference('testrole', $role3);
        $this->addReference('testerrole', $role4);
    }

    public function getOrder()
    {
        return 1;
    }
}
