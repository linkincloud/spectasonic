<?php

namespace Spectasonic\Back\RemoteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SpectasonicBackRemoteBundle:Default:index.html.twig');
    }
}
