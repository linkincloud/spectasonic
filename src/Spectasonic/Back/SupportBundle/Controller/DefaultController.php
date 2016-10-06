<?php

namespace Spectasonic\Back\SupportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SpectasonicBackSupportBundle:Default:index.html.twig');
    }
}
