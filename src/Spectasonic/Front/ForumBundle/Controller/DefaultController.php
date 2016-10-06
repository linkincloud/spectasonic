<?php

namespace Spectasonic\Front\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SpectasonicFrontForumBundle:Default:index.html.twig');
    }
}
