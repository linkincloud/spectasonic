<?php

namespace Spectasonic\Front\ContactBundle\Controller;

use Spectasonic\Front\ContactBundle\Entity\Enquiry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {

    public function indexAction(Request $request) {
        // On crée un objet Enquiry (contact)
        $enquiry = new Enquiry();

        // J'ai raccourci cette partie, car c'est plus rapide à écrire !
        $form = $this->get('form.factory')->createBuilder('form', $enquiry)
                ->add('name', 'text')
                ->add('firstname', 'text')
                ->add('email', 'email')
                ->add('Title', 'text')
                ->add('message', 'textarea')
                ->add('save', 'submit')
                ->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            // On l'enregistre notre objet $advert dans la base de données, par exemple
            $em = $this->getDoctrine()->getManager();
            $em->persist($enquiry);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Message bien enregistré.');
            // On redirige vers la page de visualisation de l'annonce nouvellement créée

            return $this->redirect($this->generateUrl('spectasonic_front_contact_confirmed'));
        }

        // À ce stade, le formulaire n'est pas valide car :
        // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
        // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
        return $this->render('SpectasonicFrontContactBundle:Default:index.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    public function confirmedAction() {
        return $this->render('SpectasonicFrontContactBundle:Default:confirmed.html.twig');
    }

}
