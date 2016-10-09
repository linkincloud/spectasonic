<?php

/**
 * Description of VendorController
 *
 * @author Mambo
 */

namespace Spectasonic\Back\UserManagerBundle\Controller;

use Spectasonic\Front\UserBundle\Entity\User;
use Spectasonic\Back\UserManagerBundle\Entity\Vendor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Spectasonic\Back\UserManagerBundle\Form\VendorType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;


class VendorController extends Controller {
    
    public function addVendorAction(Request $request) {
        $user = new User();

        $form = $this->createForm(new AddVendorType($user), $user);

        if ($form->handleRequest($request)->isValid()) {
            $userManager = $this->get('fos_user.user_manager');
            $exists = $userManager->findUserBy(array('email' => $user->getEmail()));
            if ($exists instanceof User) {
                throw new HttpException(409, 'Email already taken');
            }
            $user->setEnabled(1);
            $userManager->updateUser($user);

            $request->getSession()->getFlashBag()->add('success', "Un nouvel utilisateur a été ajouté !");

            return $this->redirect($this->generateUrl('spectasonic_back_user_manager_view', array('id' => $user->getId())));
        }

        return $this->render('SpectasonicBackUserManagerBundle:Default:adduser.html.twig', array('form' => $form->createView()));
    }
    
    public function addInformationsvendorAction(Request $request, $id) {
        $user = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('SpectasonicFrontUserBundle:User')
                ->getOneUser($id);
       
        if (null === $user) {
            throw new NotFoundHttpException("Ce vendeur ID " . $id . " n'existe pas !");
        }
      
        $vendor = new Vendor();
        $form = $this->get('form.factory')->create(new VendorType(), $vendor);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user->setMore($vendor);
            $em->persist($vendor);
            $em->persist($user);
            $em->flush();            

            $request->getSession()->getFlashBag()->add('success',"Informations Vendor have been added.");

            return $this->redirect($this->generateUrl('spectasonic_back_user_manager_view', array('id' => $user->getId())));
        }

        return $this->render('SpectasonicBackUserManagerBundle:Default:addinformationsvendor.html.twig', array(
                    'user' => $user,
                    'form' => $form->createView(),
        ));
    }
    
}
