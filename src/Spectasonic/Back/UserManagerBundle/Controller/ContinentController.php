<?php

namespace Spectasonic\Back\UserManagerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use Spectasonic\Back\UserManagerBundle\Entity\Continent;
use Spectasonic\Back\UserManagerBundle\Form\ContinentType;

use Spectasonic\Back\UserManagerBundle\Form\ContinentFilterType;

/**
 * Continent controller.
 *
 */
class ContinentController extends Controller
{
    /**
     * Lists all Continent entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('SpectasonicBackUserManagerBundle:Continent')->createQueryBuilder('e');
        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);

        list($continents, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        return $this->render('SpectasonicBackUserManagerBundle:Continent:index.html.twig', array(
            'continents' => $continents,
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
        $filterForm = $this->createForm(new ContinentFilterType());

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('ContinentControllerFilter');
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
                $session->set('ContinentControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('ContinentControllerFilter')) {
                $filterData = $session->get('ContinentControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm(new ContinentFilterType(), $filterData);
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
            return $me->generateUrl('back_manager_continent', array('page' => $page));
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
     * Displays a form to create a new Continent entity.
     *
     */
    public function newAction(Request $request)
    {
    
        $continent = new Continent();
        $form   = $this->createForm(new ContinentType(), $continent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($continent);
            $em->flush();
            
            $editLink = $this->generateUrl('back_manager_continent_edit', array('id' => $continent->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New continent was created successfully.</a>" );

            return $this->redirectToRoute('back_manager_continent');
        }
        return $this->render('SpectasonicBackUserManagerBundle:Continent:new.html.twig', array(
            'continent' => $continent,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a Continent entity.
     *
     */
    public function showAction(Continent $continent)
    {
        $deleteForm = $this->createDeleteForm($continent);
        return $this->render('SpectasonicBackUserManagerBundle:Continent:show.html.twig', array(
            'continent' => $continent,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Continent entity.
     *
     */
    public function editAction(Request $request, Continent $continent)
    {
        $deleteForm = $this->createDeleteForm($continent);
        $editForm = $this->createForm(new ContinentType(), $continent);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($continent);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('back_manager_continent_edit', array('id' => $continent->getId()));
        }
        return $this->render('SpectasonicBackUserManagerBundle:Continent:edit.html.twig', array(
            'continent' => $continent,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Continent entity.
     *
     */
    public function deleteAction(Request $request, Continent $continent)
    {
    
        $form = $this->createDeleteForm($continent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($continent);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Continent was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Continent');
        }
        
        return $this->redirectToRoute('back_manager_continent');
    }
    
    /**
     * Creates a form to delete a Continent entity.
     *
     * @param Continent $continent The Continent entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Continent $continent)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('back_manager_continent_delete', array('id' => $continent->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Continent by id
     *
     * @param mixed $id The entity id
     */
    public function deleteByIdAction(Continent $continent){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($continent);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Continent was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Continent');
        }

        return $this->redirect($this->generateUrl('back_manager_continent'));

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
                $repository = $em->getRepository('SpectasonicBackUserManagerBundle:Continent');

                foreach ($ids as $id) {
                    $continent = $repository->find($id);
                    $em->remove($continent);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'continents was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the continents ');
            }
        }

        return $this->redirect($this->generateUrl('back_manager_continent'));
    }
    

}
