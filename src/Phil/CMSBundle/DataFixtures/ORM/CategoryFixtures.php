<?php
// src/Phil/CMSBundle/DataFixtures/ORM/CategoryFixtures.php

namespace Phil\CMSBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Phil\CMSBundle\Entity\Category;

class CategoryFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $category1 = new Category();
        $category1->setName('About');
        $category1->setSlug('about');
        $category1->setMenuorder(1);
        $manager->persist($category1);

        $category2 = new Category();
        $category2->setName('Resume');
        $category2->setSlug('resume');
        $category2->setMenuorder(2);
        $manager->persist($category2);

        $category3 = new Category();
        $category3->setName('Blog');
        $category3->setSlug('blog');
        $category3->setMenuorder(3);
        $manager->persist($category3);

        $category4 = new Category();
        $category4->setName('Contact');
        $category4->setSlug('contact');
        $category4->setMenuorder(4);
        $manager->persist($category4);

        $category5 = new Category();
        $category5->setName('Images');
        $category5->setSlug('images');
        $category5->setMenuorder(5);
        $manager->persist($category5);

        $category6 = new Category();
        $category6->setName('Projects');
        $category6->setSlug('projects');
        $category6->setMenuorder(6);
        $manager->persist($category6);

        $category7 = new Category();
        $category7->setName('Invisible');
        $category7->setSlug('invis');
        $category7->setMenuorder(7);
        $category7->setInvisible();
        $manager->persist($category7);

        $manager->flush();

        $this->addReference('about', $category1);
        $this->addReference('resume', $category2);
        $this->addReference('blog', $category3);
        $this->addReference('contact', $category4);
        $this->addReference('images', $category5);
        $this->addReference('projects', $category6);
        $this->addReference('invis', $category7);
    }

    public function getOrder()
    {
        return 3;
    }
}
