<?php

namespace Spectasonic\Back\UserManagerBundle\Controller;

use Spectasonic\Front\UserBundle\Entity\User;
use Spectasonic\Front\UserBundle\Entity\Address;
use Spectasonic\Front\UserBundle\Form\AddressType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Spectasonic\Front\UserBundle\Form\Type\AddUserType;
use Spectasonic\Front\UserBundle\Form\Type\ProfileUserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
                ->getRepository('SpectasonicFrontUserBundle:User');

        $listUsers = $repository->getAllUsers();

        return $this->render('SpectasonicBackUserManagerBundle:Default:index.html.twig', array('listUsers' => $listUsers));
    }

    public function viewUserAction($id) {
        //$repository = $this->getDoctrine()->getManager()->getRepository('SpectasonicFrontUserBundle:User');
        //$user = $repository->getOneUser($id);

        $user = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('SpectasonicFrontUserBundle:User')
                ->getOneUser($id);

        if (null === $user) {
            throw new NotFoundHttpException("Cette utilisateur ID " . $id . " n'existe pas !");
        }

        return $this->render('SpectasonicBackUserManagerBundle:Default:viewuser.html.twig', array('user' => $user));
    }

    public function editUserAction(Request $request, $id) {
        $user = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('SpectasonicFrontUserBundle:User')
                ->getOneUser($id);

        if (null === $user) {
            throw new NotFoundHttpException("Cette utilisateur ID " . $id . " n'existe pas !");
        }

        $form = $this->createForm(new ProfileUserType($user), $user);

        if ($form->handleRequest($request)->isValid()) {
            $userManager = $this->get('fos_user.user_manager');
            $exists = $userManager->findUserBy(array('email' => $user->getEmail()));

            if ($exists instanceof User) {
                if ($user->getId() != $exists->getId()) {
                    throw new HttpException(409, 'Email already taken');
                }
            }

            $userManager->updateUser($user);

            $request->getSession()->getFlashBag()->add('success', "L'utilisateur a été bien modifié.");

            return $this->redirect($this->generateUrl('spectasonic_back_user_manager_view', array('id' => $user->getId())));
        }

        return $this->render('SpectasonicBackUserManagerBundle:Default:edituser.html.twig', array(
                    'form' => $form->createView(),
                    'user' => $user,
        ));
    }

    public function addUserAction(Request $request) {
        $user = new User();

        $form = $this->createForm(new AddUserType($user), $user);

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

    public function editAddressAction(Request $request, $addressId, $userId) {
        $user = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('SpectasonicFrontUserBundle:User')
                ->getOneUser($id);

        if (null === $user) {
            throw new NotFoundHttpException("Cette utilisateur ID " . $id . " n'existe pas !");
        }

        $form = $this->createForm(new ProfileUserType($user), $user);

        if ($form->handleRequest($request)->isValid()) {
            $userManager = $this->get('fos_user.user_manager');
            $exists = $userManager->findUserBy(array('email' => $user->getEmail()));

            if ($exists instanceof User) {
                if ($user->getId() != $exists->getId()) {
                    throw new HttpException(409, 'Email already taken');
                }
            }

            $userManager->updateUser($user);

            $request->getSession()->getFlashBag()->add('success', "L'utilisateur a été bien modifié.");

            return $this->redirect($this->generateUrl('spectasonic_back_user_manager_view', array('id' => $user->getId())));
        }

        return $this->render('SpectasonicBackUserManagerBundle:Default:edituser.html.twig', array(
                    'form' => $form->createView(),
                    'user' => $user,
        ));
    }

    
    public function addAddressAction(Request $request, $id, $type) {
        $user = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('SpectasonicFrontUserBundle:User')
                ->getOneUser($id);
       
        if (null === $user) {
            throw new NotFoundHttpException("Cette utilisateur ID " . $id . " n'existe pas !");
        }
      
        $address = new Address();
        $form = $this->get('form.factory')->create(new AddressType(), $address);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $address->setType(strtoupper($type));
            $user->addAddress($address);
            $em->persist($address);
            $em->persist($user);
            $em->flush();            

            $request->getSession()->getFlashBag()->add('success', ucfirst(($type))." Address has been added.");

            return $this->redirect($this->generateUrl('spectasonic_back_user_manager_view', array('id' => $user->getId())));
        }

        return $this->render('SpectasonicBackUserManagerBundle:Default:addaddress.html.twig', array(
                    'user' => $user,
                    'type' => $type,
                    'form' => $form->createView(),
        ));
    }
    
    public function addBillingAddressAction(Request $request, $id) {
        $user = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('SpectasonicFrontUserBundle:User')
                ->getOneUser($id);
        
        
        
        if (null === $user) {
            throw new NotFoundHttpException("Cette utilisateur ID " . $id . " n'existe pas !");
        }

        $address = new Address();
        $form = $this->get('form.factory')->create(new AddressType(), $address);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $address->setType('BILLING');
            $user->addAddress($address);
            $em->persist($address);
            $em->persist($user);
            $em->flush();            

            $request->getSession()->getFlashBag()->add('success', "Billing Address has been added.");

            return $this->redirect($this->generateUrl('spectasonic_back_user_manager_view', array('id' => $user->getId())));
        }

        return $this->render('SpectasonicBackUserManagerBundle:Default:addaddress.html.twig', array(
                    'user' => $user,
                    'type' => 'Billing',
                    'form' => $form->createView(),
        ));
    }
    
    /*
     * On pourrait factoriser ces deux méthodes en passant un GET type=shipping / billing
     * Et récupérer dans le request
     */
    public function addShippingAddressAction(Request $request, $id) {
        $user = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('SpectasonicFrontUserBundle:User')
                ->getOneUser($id);

        if (null === $user) {
            throw new NotFoundHttpException("Cette utilisateur ID " . $id . " n'existe pas !");
        }

        $address = new Address();
        $form = $this->get('form.factory')->create(new AddressType(), $address);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $address->setType('SHIPPING');
            $user->addAddress($address);
            $em->persist($address);
            $em->persist($user);
            $em->flush();            

            $request->getSession()->getFlashBag()->add('success', "Shipping Address has been added.");

            return $this->redirect($this->generateUrl('spectasonic_back_user_manager_view', array('id' => $user->getId())));
        }

        return $this->render('SpectasonicBackUserManagerBundle:Default:addaddress.html.twig', array(
                    'user' => $user,
                    'type' => 'Shipping',
                    'form' => $form->createView(),
        ));
    }

    /**
     * Pour Tester
     * @return type object
     */
   /* public function addAddressAction() {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('SpectasonicFrontUserBundle:User');
        $user = $repository->find(6);

        $addressBilling = new Address();
        $addressBilling->setLine1("2 chemin de la Croix d'Evieu");
        $addressBilling->setCode('38110');
        $addressBilling->setCity('La Tour du Pin');
        $addressBilling->setCountry('France');
        $addressBilling->setType('BILLING');

        $addressShipping = new Address();
        $addressShipping->setLine1("730 route de Beaudinard");
        $addressShipping->setCode('13400');
        $addressShipping->setCity('Aubagne');
        $addressShipping->setCountry('France');
        $addressShipping->setType('SHIPPING');

        // Link $address to user
        $addressBilling->setUser($user);
        $addressShipping->setUser($user);

        $em->persist($addressBilling);
        $em->persist($addressShipping);

        $em->flush();

        return $this->render('SpectasonicBackUserManagerBundle:Default:addaddress.html.twig', array(
                    'user' => $user,
                    'addressBilling' => $addressBilling,
                    'addressShippin' => $addressShipping
        ));
    }*/

}
