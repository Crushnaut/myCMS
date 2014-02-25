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

    public function submenuAction($slug, $subslug = null)
    {
        $em = $this->getDoctrine()
                   ->getManager();

        $categoryId = $em->getRepository('PhilCMSBundle:Page')
                         ->findOneBySlug($slug)
                         ->getCategory()
                         ->getId();

        $pages = $em->getRepository('PhilCMSBundle:Page')
                    ->getPagesFromCategory($categoryId);

        if (is_null($subslug))
        {   
            return $this->render('PhilCMSBundle:Menu:sub.html.twig', array('pages' => $pages, 'slug' => $slug));
        }

        return $this->render('PhilCMSBundle:Menu:sub.html.twig', array('pages' => $pages, 'slug' => $subslug));
    }
}