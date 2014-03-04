<?php
// src/Phil/CMSBundle/Controller/ContentController.php

namespace Phil\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ContentController extends Controller
{
	public function aboutAction()
	{
		return $this->render('PhilCMSBundle:Content:about.html.twig');
	}

    public function showAction($slug, $subslug = null)
    {
        $em = $this->getDoctrine()
                   ->getManager();

        if (is_null($subslug))
        {
            $page = $em->getRepository('PhilCMSBundle:Page')
                       ->findOneBySlug($slug);
        } else 
        {
            $page = $em->getRepository('PhilCMSBundle:Page')
                       ->findOneBySlug($subslug);
        }

        //if (false === $this->get('security.context')->isGranted($page->getViewRole()->getRole()))
       // {
        //    throw new AccessDeniedException();
        //}

        return $this->render('PhilCMSBundle:Content:show.html.twig', array('page' => $page));
    }
}
