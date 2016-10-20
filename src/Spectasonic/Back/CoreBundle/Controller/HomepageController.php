<?php

namespace Spectasonic\Back\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Spectasonic\Back\CoreBundle\Entity\Homepage;
use Spectasonic\Back\CoreBundle\Form\HomepageType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Homepage controller.
 *
 */
class HomepageController extends Controller {

    /**
     * Displays a form to edit an existing Homepage entity.
     *
     */
    public function editAction(Request $request) {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins');
        }

        $em = $this->getDoctrine()->getManager();

        //D'abord on vérifie s'il y a au moins un Header/Slider créé en base
        $sliders = $em
                ->getRepository('SpectasonicBackCoreBundle:Slider')
                ->findAll();
       
        if (sizeof($sliders) === 0) {
            $this->get('session')->getFlashBag()->add('info', 'You must create One Slider for Homepage !');
            return $this->redirectToRoute('spectasonic_back_configuration_homepage_slider_new');
        }

        $homepage = $em
                ->getRepository('SpectasonicBackCoreBundle:Homepage')
                ->find(1);

        if ($homepage === null) {
            $slider = $sliders[0];
            //throw new NotFoundHttpException("Please ! contact now LinkInCloud");
            // Il faut aller créer une Homepage par défaut
            $newHomepage = new Homepage();
            $newHomepage->setEnabled(false);
            $newHomepage->setSlider($slider);
            $em->persist($newHomepage);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'We have created a default configuration for Homepage !');
            return $this->redirectToRoute('spectasonic_back_configuration_homepage');
        }
     
        $editForm = $this->createForm(new HomepageType(), $homepage);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($homepage);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('spectasonic_back_configuration_homepage', array('id' => $homepage->getId()));
        }
        return $this->render('SpectasonicBackCoreBundle:Homepage:edit.html.twig', array(
                    'homepage' => $homepage,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to delete a Homepage entity.
     *
     * @param Homepage $homepage The Homepage entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Homepage $homepage) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('back_configuration_homepage_delete', array('id' => $homepage->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
