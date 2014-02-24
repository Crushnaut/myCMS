<?php
// src/Phil/CMSBundle/Controller/MenuController.php

namespace Phil\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MenuController extends Controller
{
    public function mainmenuAction($slug)
    {
        $em = $this->getDoctrine()
                   ->getManager();

        $cats = $em->getRepository('PhilCMSBundle:Category')
                   ->getVisibleCategories();

        return $this->render('PhilCMSBundle:Menu:main.html.twig', array('cats' => $cats, 'slug' => $slug));
    }

    public function submenuAction($slug)
    {

    }
}