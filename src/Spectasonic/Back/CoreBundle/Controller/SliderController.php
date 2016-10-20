<?php

namespace Spectasonic\Back\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use Spectasonic\Back\CoreBundle\Entity\Slider;
use Spectasonic\Back\CoreBundle\Form\SliderType;

use Spectasonic\Back\CoreBundle\Form\SliderFilterType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Slider controller.
 *
 */
class SliderController extends Controller
{
    /**
     * Lists all Slider entities.
     *
     */
    public function indexAction(Request $request)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins');
        }
        //$em = $this->getDoctrine()->getManager();
        //$queryBuilder = $em->getRepository('SpectasonicBackCoreBundle:Slider')->createQueryBuilder('e');
        
        $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('SpectasonicBackCoreBundle:Slider');

        $qb = $repository->getAll();
        list($filterForm, $qb) = $this->filter($qb, $request);

        list($sliders, $pagerHtml) = $this->paginator($qb, $request);
        
        return $this->render('SpectasonicBackCoreBundle:Slider:index.html.twig', array(
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
            return $me->generateUrl('spectasonic_back_configuration_homepage_slider', array('page' => $page));
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
     *
     */
    public function newAction(Request $request)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins');
        }
    
        $slider = new Slider();
        $form   = $this->createForm(new SliderType(), $slider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($slider);
            $em->flush();

            return $this->redirectToRoute('spectasonic_back_configuration_homepage_slider_edit', array('id' => $slider->getId()));
        }
        return $this->render('SpectasonicBackCoreBundle:Slider:new.html.twig', array(
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
        return $this->render('SpectasonicBackCoreBundle:Slider:show.html.twig', array(
            'slider' => $slider,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Slider entity.
     *
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
            return $this->redirectToRoute('spectasonic_back_configuration_homepage_slider_edit', array('id' => $slider->getId()));
        }
        return $this->render('SpectasonicBackCoreBundle:Slider:edit.html.twig', array(
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
            $this->get('session')->getFlashBag()->add('success', 'The Slider was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Slider');
        }
        
        return $this->redirectToRoute('spectasonic_back_configuration_homepage_slider');
    }
    
    /**
     * Creates a form to delete a Slider entity.
     *
     * @param Slider $slider The Slider entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Slider $slider)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins');
        }
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('spectasonic_back_configuration_homepage_slider_delete', array('id' => $slider->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Slider by id
     *
     * @param mixed $id The entity id
     */
    public function deleteByIdAction(Slider $slider){
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins');
        }
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($slider);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Slider was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Slider');
        }

        return $this->redirect($this->generateUrl('spectasonic_back_configuration_homepage_slider'));

    }
    
    
    
    /**
    * Bulk Action
    */
    public function bulkAction(Request $request)
    {
        $ids = $request->get("ids", array());
        //$action = $request->get("bulk_action", "delete");
        $action = $request->get("spectasonic_back_configuration_homepage_slider_bulk_action", "delete");

        if ($action == "delete") {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('SpectasonicBackCoreBundle:Slider');

                foreach ($ids as $id) {
                    $slider = $repository->find($id);
                    $em->remove($slider);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'homepages was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the homepages ');
            }
        }

        return $this->redirect($this->generateUrl('spectasonic_back_configuration_homepage_slider'));
    }
    
    
}
