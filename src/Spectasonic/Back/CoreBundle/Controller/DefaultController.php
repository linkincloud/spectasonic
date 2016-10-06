<?php

namespace Spectasonic\Back\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SpectasonicBackCoreBundle:Default:index.html.twig');
    }
}
