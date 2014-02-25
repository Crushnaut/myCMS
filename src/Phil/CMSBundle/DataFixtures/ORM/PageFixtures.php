<?php
// src/Phil/CMSBundle/DataFixtures/ORM/PageFixtures.php

namespace Phil\CMSBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Phil\CMSBundle\Entity\Page;
use Phil\CMSBundle\Entity\Category;

class CommentFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $page = new Page();
        $page->setTitle('About');
        $page->setSlug('about');
        $page->setCategory($manager->merge($this->getReference('cat-1')));
        $page->setContent("Place holder for the about page content.");
        $manager->persist($page);

        $page = new Page();
        $page->setTitle('About the Author');
        $page->setSlug('aboutauthor');
        $page->setCategory($manager->merge($this->getReference('cat-1')));
        $page->setContent("Place holder for the about the author page content.");
        $manager->persist($page);

        $page = new Page();
        $page->setTitle('Resume');
        $page->setSlug('resume');
        $page->setCategory($manager->merge($this->getReference('cat-2')));
        $page->setContent("Place holder for the resume page content.");
        $manager->persist($page);

        $page = new Page();
        $page->setTitle('Development Blog');
        $page->setSlug('blog');
        $page->setCategory($manager->merge($this->getReference('cat-3')));
        $page->setContent("Place holder for the development blog content.");
        $manager->persist($page);

        $page = new Page();
        $page->setTitle('Personal Blog');
        $page->setSlug('personalblog');
        $page->setCategory($manager->merge($this->getReference('cat-3')));
        $page->setContent("Place holder for the personal blog content.");
        $manager->persist($page);

        $page = new Page();
        $page->setTitle('Contact');
        $page->setSlug('contact');
        $page->setCategory($manager->merge($this->getReference('cat-4')));
        $page->setContent("Place holder for the contact page content.");
        $manager->persist($page);

        $page = new Page();
        $page->setTitle('Images');
        $page->setSlug('images');
        $page->setCategory($manager->merge($this->getReference('cat-5')));
        $page->setContent("Place holder for the images page content.");
        $manager->persist($page);

        $page = new Page();
        $page->setTitle('Screenshots');
        $page->setSlug('screens');
        $page->setCategory($manager->merge($this->getReference('cat-5')));
        $page->setContent("Place holder for the screenshots page content.");
        $manager->persist($page);

        $page = new Page();
        $page->setTitle('Projects');
        $page->setSlug('projects');
        $page->setCategory($manager->merge($this->getReference('cat-6')));
        $page->setContent("Place holder for the projects page content.");
        $manager->persist($page);

        $page = new Page();
        $page->setTitle('Websites');
        $page->setSlug('websites');
        $page->setCategory($manager->merge($this->getReference('cat-6')));
        $page->setContent("Place holder for the websites page content.");
        $manager->persist($page);

        $page = new Page();
        $page->setTitle('RPGs');
        $page->setSlug('rpgs');
        $page->setCategory($manager->merge($this->getReference('cat-6')));
        $page->setContent("Place holder for the RPG page content.");
        $manager->persist($page);

        $page = new Page();
        $page->setTitle('Writing');
        $page->setSlug('writing');
        $page->setCategory($manager->merge($this->getReference('cat-6')));
        $page->setContent("Place holder for the writing page content.");
        $manager->persist($page);

        $page = new Page();
        $page->setTitle('Photoshop');
        $page->setSlug('photoshop');
        $page->setCategory($manager->merge($this->getReference('cat-6')));
        $page->setContent("Place holder for the photoshop page content.");
        $manager->persist($page);

        $page = new Page();
        $page->setTitle('CSS');
        $page->setSlug('css');
        $page->setCategory($manager->merge($this->getReference('cat-6')));
        $page->setContent("Place holder for the CSS page content.");
        $manager->persist($page);

        $page = new Page();
        $page->setTitle('PhP');
        $page->setSlug('php');
        $page->setCategory($manager->merge($this->getReference('cat-6')));
        $page->setContent("Place holder for the PhP page content.");
        $manager->persist($page);

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}