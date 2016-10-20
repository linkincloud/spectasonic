<?php

namespace Spectasonic\Back\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class DefaultController extends Controller
{

    
    public function indexAction(Request $request)
    {
        // On vérifie que l'utilisateur dispose bien du rôle
    /*    if (!$this->get('security.context')->isGranted('ROLE_EDITEUR')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux editeurs.');
        }*/

        /*
         * Si je suis un admin (ROLE_ADMIN) alors j'ai accès à tous les posts
         * */

        /*if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {

            $em = $this->getDoctrine()->getManager();
            /* C'est pour l'admin */
        /*    $queryBuilder = $em->getRepository('SpectasonicBackCoreBundle:Homepage')->createQueryBuilder('e');

            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux administrateurs.');

        }
        if (!$this->get('security.context')->isGranted('ROLE_EDITEUR')) {

            $em = $this->getDoctrine()->getManager();

            $queryBuilder = $em->getRepository('SpectasonicBackCoreBundle:Homepage')->createQueryBuilder('f');

            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux editeurs.');

        }*/


        return $this->render('SpectasonicBackCoreBundle:Default:index.html.twig');
    }
}
