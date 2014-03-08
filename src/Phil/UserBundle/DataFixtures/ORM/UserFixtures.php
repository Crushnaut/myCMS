<?php
// src/Phil/UserBundle/DataFixtures/ORM/UserFixtures.php

namespace Phil\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Phil\UserBundle\Entity\User;
use Phil\UserBundle\Entity\Role;

class UserFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setUsername('pmowatt');
        $user1->setFirstname('Phil');
        $user1->setLastname('Mowatt');
        $user1->setBirthday(\DateTime::createFromFormat('m-d-Y', '04-14-1985'));
        $user1->setpassword('qwerty');
        $user1->setEmail('pmowatt@philmowatt.com');
        $user1->setEnabled(true);
        $user1->addRole($manager->merge($this->getReference('userrole')));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setUsername('root');
        $user2->setFirstname('Root');
        $user2->setLastname('Admin');
        $user2->setBirthday(\DateTime::createFromFormat('m-d-Y', '04-10-1975'));
        $user2->setpassword('asdfgh');
        $user2->setEmail('omegared@philmowatt.com');
        $user2->setEnabled(true);
        $user2->addRole($manager->merge($this->getReference('adminrole')));
        $manager->persist($user2);

        $user3 = new User();
        $user3->setUsername('crushnaut');
        $user3->setFirstname('Crush');
        $user3->setLastname('Naut');
        $user3->setBirthday(\DateTime::createFromFormat('m-d-Y', '04-20-1995'));
        $user3->setpassword('qwerty');
        $user3->setEmail('crushnaut@philmowatt.com');
        $user3->setEnabled(true);
        $user3->addRole($manager->merge($this->getReference('userrole')));
        $user3->addRole($manager->merge($this->getReference('adminrole')));
        $manager->persist($user3);

        $manager->flush();

        $this->addReference('pmowatt', $user1);
        $this->addReference('root', $user2);
        $this->addReference('crushnaut', $user3);
    }

    public function getOrder()
    {
        return 2;
    }
}
