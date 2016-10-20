<?php

namespace Spectasonic\Back\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use Spectasonic\Back\BlogBundle\Entity\Blog;
use Spectasonic\Back\BlogBundle\Form\BlogType;

use Spectasonic\Back\BlogBundle\Form\BlogFilterType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Blog controller.
 *
 */
class BlogController extends Controller
{
    public function indexAction(Request $request)
    {
        /* Sécurité */
        if (($this->get('security.context')->isGranted('ROLE_ADMIN'))
            OR
            ($this->get('security.context')->isGranted('ROLE_EDITEUR'))) {
        }else{
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins et aux vendeurs');
        }
        /* Ne pas oublier d'optimiser l'expérience utilisateur dans la vue
        * Affichage uniquement les boutons Actions selon le ROLE et l'appartenance
        *
        * Fin de la sécurité
        **/

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('SpectasonicBackBlogBundle:Blog')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);

        list($blogs, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        return $this->render('SpectasonicBackBlogBundle:Blog:index.html.twig', array(
            'blogs' => $blogs,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),

        ));
    }

    
    /**
    * Create filter form and process filter request.
    *
    */
    protected function filter($queryBuilder, $request)
    {
        $session = $request->getSession();
        $filterForm = $this->createForm(new BlogFilterType());

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('BlogControllerFilter');
        }

        // Filter action
        if ($request->get('filter_action') == 'filter') {
            // Bind values from the request
            $filterForm->submit($request->query->get($filterForm->getName()));

            if ($filterForm->isValid()) {
                // Build the query from the given form object
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
                // Save filter to session
                $filterData = $filterForm->getData();
                $session->set('BlogControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('BlogControllerFilter')) {
                $filterData = $session->get('BlogControllerFilter');
                $filterForm = $this->createForm(new BlogFilterType(), $filterData);
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
            }
        }

        return array($filterForm, $queryBuilder);
    }

    /**
    * Get results from paginator and get paginator view.
    *
    */
    protected function paginator($queryBuilder, $request)
    {
        // Paginator
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $currentPage = $request->get('page', 1);
        $pagerfanta->setCurrentPage($currentPage);
        $entities = $pagerfanta->getCurrentPageResults();

        // Paginator - route generator
        $me = $this;
        $routeGenerator = function($page) use ($me)
        {
            return $me->generateUrl('back_blog', array('page' => $page));
        };

        // Paginator - view
        $view = new TwitterBootstrap3View();
        $pagerHtml = $view->render($pagerfanta, $routeGenerator, array(
            'proximity' => 3,
            'prev_message' => 'previous',
            'next_message' => 'next',
        ));

        return array($entities, $pagerHtml);
    }
    
    

    /**
     * Displays a form to create a new Blog entity.
     *
     */
    public function newAction(Request $request)
    {
        /*
         * Sécurité
         */
        if (($this->get('security.context')->isGranted('ROLE_ADMIN'))
            OR
            ($this->get('security.context')->isGranted('ROLE_EDITEUR'))) {
        }else{
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins et aux editeurs');
        }
        /*
         * Fin de la sécurité
         */

        $blog = new Blog();
        $form   = $this->createForm(new BlogType(), $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();

            return $this->redirectToRoute('back_blog_show', array('id' => $blog->getId()));
        }
        return $this->render('SpectasonicBackBlogBundle:Blog:new.html.twig', array(
            'blog' => $blog,
            'form'   => $form->createView(),
        ));
    }
    
    

    
    /**
     * Finds and displays a Blog entity.
     */
    public function showAction(Blog $blog)
    {
        /*
         * Sécurité
         */
        if (
            (
                ( $this->get('security.token_storage')->getToken()->getUser()->getId() === $blog->getAuthor()->getId() )
                AND
                ($this->container->get('security.authorization_checker')->isGranted('ROLE_EDITEUR'))
            )
            OR
            ( $this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        ) {
            /* On ne fait rien
            */
        }else{
            throw new AccessDeniedException('Vous ne pouvez pas accèder à cette page');
        }
        /*
         * Fin de la sécurité
         */

        $deleteForm = $this->createDeleteForm($blog);
        return $this->render('SpectasonicBackBlogBundle:Blog:show.html.twig', array(
            'blog' => $blog,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Blog entity.
     *
     */
    public function editAction(Request $request, Blog $blog)
    {
        /* Sécurité */
        if (
            (
                ( $this->get('security.token_storage')->getToken()->getUser()->getId() === $blog->getAuthor()->getId() )
                AND
                ($this->container->get('security.authorization_checker')->isGranted('ROLE_EDITEUR'))
            )
            OR
            ( $this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        ) {
            /* On ne fait rien
            */
        }else{
            throw new AccessDeniedException('Vous ne pouvez pas accèder à cette page');
        }
        /* Fin de la sécurité */

        $deleteForm = $this->createDeleteForm($blog);
        $editForm = $this->createForm(new BlogType(), $blog);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('back_blog_edit', array('id' => $blog->getId()));
        }
        return $this->render('SpectasonicBackBlogBundle:Blog:edit.html.twig', array(
            'blog' => $blog,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Blog entity.
     *
     */

    public function deleteAction(Request $request, Blog $blog)
    {
        /* Début de la sécurité */
        if (
            (
                ( $this->get('security.token_storage')->getToken()->getUser()->getId() === $blog->getAuthor()->getId() )
                AND
                ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
            )
        ) {
            /* On ne fait rien
            */
        }else{
            throw new AccessDeniedException('Vous ne pouvez pas accèder à cette page');
        }
        /* Fin de la sécurité */

        $form = $this->createDeleteForm($blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($blog);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }
        
        return $this->redirectToRoute('back_blog');
    }
    
    /**
     * Creates a form to delete a Blog entity.
     *
     * @param Blog $blog The Blog entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Blog $blog)
    {
        /* Sécurité */
        if (
        (
            ( $this->get('security.token_storage')->getToken()->getUser()->getId() === $blog->getAuthor()->getId() )
            AND
            ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        )
        ) {
            /* On ne fait rien
            */
        }else{
            throw new AccessDeniedException('Vous ne pouvez pas accèder à cette page');
        }
        /* Fin de la sécurité */

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('back_blog_delete', array('id' => $blog->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Blog by id
     * @param mixed $id The entity id
     * @Security("has_role('ROLE_ADMIN') ")
     */
    public function deleteById($id){
        $em = $this->getDoctrine()->getManager();
        $blog = $em->getRepository('SpectasonicBackBlogBundle:Blog')->find($id);

        if (!$blog) {
            throw $this->createNotFoundException('Unable to find Blog entity.');
        }

        /* Sécurité */
        if (
        (
            ( $this->get('security.token_storage')->getToken()->getUser()->getId() === $blog->getAuthor()->getId() )
            AND
            ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        )
        ) {
            /* On ne fait rien
            */
        }else{
            throw new AccessDeniedException('Vous ne pouvez pas accèder à cette page');
        }
        /* Fin de la sécurité */

        try {
            $em->remove($blog);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('back_blog'));

    }
    
    
    
    /**
    * Bulk Action
    */
    public function bulkAction(Request $request)
    {
        $ids = $request->get("ids", array());
        $action = $request->get("bulk_action", "delete");

        if ($action == "delete") {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('SpectasonicBackBlogBundle:Blog');

                foreach ($ids as $id) {
                    $blog = $repository->find($id);

                    /* Sécurité */
                    if (
                    (
                        ( $this->get('security.token_storage')->getToken()->getUser()->getId() === $blog->getAuthor()->getId() )
                        AND
                        ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
                    )
                    ) {
                        $em->remove($blog);
                        $em->flush();

                        $this->get('session')->getFlashBag()->add('success', 'blogs was deleted successfully!');

                    }else{
                        // Ici on ne fait rien
                        // new AccessDeniedException('Vous ne pouvez pas accèder à cette page');
                    }
                    /* Fin de la sécurité */
                }

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the blogs ');
            }
        }

        return $this->redirect($this->generateUrl('back_blog'));
    }
    
    
}
