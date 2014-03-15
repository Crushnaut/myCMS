<?php
// src/Phil/UserBundle/Controller/MenuController.php

namespace Phil\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MenuController extends Controller
{
    /*
     *  Action for generating the user menu.
     */
    public function displayAction()
    {
        return $this->render('PhilUserBundle:Menu:main.html.twig');
    }

    public function sidebarAction()
    {
        return $this->render('PhilUserBundle:Menu:side.html.twig');
    }
}
