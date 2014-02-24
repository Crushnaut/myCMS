<?php
// src/Phil/CMSBundle/Controller/ContentController.php

namespace Phil\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContentController extends Controller
{
	public function aboutAction()
	{
		return $this->render('PhilCMSBundle:Content:about.html.twig');
	}

    public function showAction($slug)
    {
        return $this->render('PhilCMSBundle:Content:show.html.twig');
    }
}