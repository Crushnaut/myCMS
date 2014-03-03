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
        $user1->setpassword('qwerty');
        $user1->setEmail('pmowatt@gmail.com');
        $user1->addRole($manager->merge($this->getReference('userrole')));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setUsername('root');
        $user2->setpassword('asdfgh');
        $user2->setEmail('omegared@gmail.com');
        $user2->addRole($manager->merge($this->getReference('userrole')));
        $user2->addRole($manager->merge($this->getReference('adminrole')));
        $manager->persist($user2);

        $user3 = new User();
        $user3->setUsername('crushnaut');
        $user3->setpassword('qwerty');
        $user3->setEmail('crushnaut@gmail.com');
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
