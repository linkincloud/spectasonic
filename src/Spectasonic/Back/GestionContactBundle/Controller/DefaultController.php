<?php

namespace Spectasonic\Back\GestionContactBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller {

    /**
     * lister tous les utilisateurs sans paramètre
     * @return array listUsers
     */
    public function indexAction() {
        //$repository = $this->getDoctrine()->getManager()->getRepository('SpectasonicFrontUserBundle:User');
        //$listUsers = $repository->findAll();

        $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('SpectasonicFrontContactBundle:Enquiry');

        $listContacts = $repository->getAllContacts();

        return $this->render('SpectasonicBackGestionContactBundle:Default:index.html.twig', array('listContacts' => $listContacts));
    }

    /**
     * Afficher enquiry
     * @param type $id (enquiry)
     * @return object enquiry
     */
    public function viewAction($id) {
        $enquiry = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('SpectasonicFrontContactBundle:Enquiry')
                ->getOneContact($id);

        if (null === $enquiry) {
            throw new NotFoundHttpException("Ce contact ID " . $id . " n'existe pas !");
        }

        return $this->render('SpectasonicBackGestionContactBundle:Default:view.html.twig', array('enquiry' => $enquiry));
    }
    
    public function deleteAction($id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $enquiry = $em->getRepository('SpectasonicFrontContactBundle:Enquiry')->find($id);

        if (null === $enquiry) {
            throw new NotFoundHttpException("Ce contact ID " . $id . " n'existe pas !");
        }      

        $form = $this->createFormBuilder()->getForm();
        
        if( $form->handleRequest($request)->isValid()){
            $em->remove($enquiry);
            $em->flush();
                
            $request->getSession()->getFlashBag()->add('info', 'This contact has been deleted.');
            return $this->redirect($this->generateUrl('spectasonic_back_gestion_contact_homepage'));
        }
        
         return $this->render('SpectasonicBackGestionContactBundle:Default:delete.html.twig', array(
                    'enquiry' => $enquiry,
                    'form' => $form->createView(),
        ));
    }

   
}
