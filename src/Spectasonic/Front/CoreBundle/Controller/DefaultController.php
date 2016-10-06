<?php

namespace Spectasonic\Front\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller {

    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        
        $homepage = $em
                ->getRepository('SpectasonicBackCoreBundle:Homepage')
                ->getFrontConfigurationHomepage(1);

   
        /* Mylène
         * $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('SpectasonicBackBlogBundle:Slider');
        
        $slider = $repository->getSlider(4);*/
                
        
        return $this->render('SpectasonicFrontCoreBundle:Default:index.html.twig', array('homepage' => $homepage));
    }
 

}
