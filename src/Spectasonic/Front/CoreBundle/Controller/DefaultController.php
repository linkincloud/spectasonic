<?php

namespace Spectasonic\Front\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

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

          $slider = $repository->getSlider(4); */


        return $this->render('SpectasonicFrontCoreBundle:Default:index.html.twig', array('homepage' => $homepage));
    }

    public function selectLangAction(Request $request, $_langue = null) {
        $urlReferer = $request->headers->get('referer');
        $pattern = '#/fr/#';
        if (preg_match($pattern, $urlReferer)) {
            $return = preg_replace($pattern, '/en/', $urlReferer);
        }
        $pattern = '#/en/#';
        if (preg_match($pattern, $urlReferer)) {
            $return = preg_replace($pattern, '/fr/', $urlReferer);
        }
        return $this->redirect($return);
    }

}
