<?php

namespace Spectasonic\Back\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use Spectasonic\Back\BlogBundle\Entity\Slider;
use Spectasonic\Back\BlogBundle\Form\SliderType;

use Spectasonic\Back\BlogBundle\Form\SliderFilterType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Slider controller.
 *
 */
class BlogSliderController extends Controller
{
    /**
     * Lists all Slider entities.
     *
     */
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
        $queryBuilder = $em->getRepository('SpectasonicBackBlogBundle:Slider')->createQueryBuilder('e');
        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);

        list($sliders, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        return $this->render('SpectasonicBackBlogBundle:Slider:index.html.twig', array(
            'sliders' => $sliders,
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
        $filterForm = $this->createForm(new SliderFilterType());

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('SliderControllerFilter');
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
                $session->set('SliderControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('SliderControllerFilter')) {
                $filterData = $session->get('SliderControllerFilter');
                $filterForm = $this->createForm(new SliderFilterType(), $filterData);
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
            return $me->generateUrl('spectasonic_back_blog_sliders', array('page' => $page));
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
     * Displays a form to create a new Slider entity.
     */
    public function newAction(Request $request)
    {
        /* Sécurité */
        if (($this->get('security.context')->isGranted('ROLE_ADMIN'))
            OR
            ($this->get('security.context')->isGranted('ROLE_EDITEUR'))) {
        }else{
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins et aux editeurs');
        }
        /* 
        * Fin de la sécurité
        **/
    
        $slider = new Slider();
        $form   = $this->createForm(new SliderType(), $slider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($slider);
            $em->flush();

            return $this->redirectToRoute('spectasonic_back_blog_sliders_show', array('id' => $slider->getId()));
        }
        return $this->render('SpectasonicBackBlogBundle:Slider:new.html.twig', array(
            'slider' => $slider,
            'form'   => $form->createView(),
        ));
    }
    
    

    
    /**
     * Finds and displays a Slider entity.
     *
     */
    public function showAction(Slider $slider)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins');
        }
        $deleteForm = $this->createDeleteForm($slider);
        
       
        
        return $this->render('SpectasonicBackBlogBundle:Slider:show.html.twig', array(
            'slider' => $slider,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Slider entity.
     *
     * @Route("/{id}/edit", name="back_blog_slider_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Slider $slider)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins');
        }
        $deleteForm = $this->createDeleteForm($slider);
        $editForm = $this->createForm(new SliderType(), $slider);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($slider);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('spectasonic_back_blog_sliders_edit', array('id' => $slider->getId()));
        }
        return $this->render('SpectasonicBackBlogBundle:Slider:edit.html.twig', array(
            'slider' => $slider,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Slider entity.
     *
     */
    public function deleteAction(Request $request, Slider $slider)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins');
        }
    
        $form = $this->createDeleteForm($slider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($slider);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }
        
        return $this->redirectToRoute('spectasonic_back_blog_sliders');
    }
    
    /**
     * Creates a form to delete a Slider entity.
     *
     */
    private function createDeleteForm(Slider $slider)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins');
        }
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('spectasonic_back_blog_sliders_delete', array('id' => $slider->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Slider by id
     *
     * @param mixed $id The entity id
     * @Route("/delete/{id}", name="back_blog_slider_by_id_delete")
     * @Method("GET")
     */
    public function deleteById($id){
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins');
        }
        $em = $this->getDoctrine()->getManager();
        $slider = $em->getRepository('SpectasonicBackBlogBundle:Slider')->find($id);
        
        if (!$slider) {
            throw $this->createNotFoundException('Unable to find Slider entity.');
        }
        
        try {
            $em->remove($slider);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('spectasonic_back_blog_slider'));

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
                $repository = $em->getRepository('SpectasonicBackBlogBundle:Slider');

                foreach ($ids as $id) {
                    $slider = $repository->find($id);
                    $em->remove($slider);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'sliders was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the sliders ');
            }
        }

        return $this->redirect($this->generateUrl('spectasonic_back_blog_sliders'));
    }
    
    
}
