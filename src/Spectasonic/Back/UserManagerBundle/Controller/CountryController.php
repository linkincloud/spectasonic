<?php

namespace Spectasonic\Back\UserManagerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use Spectasonic\Back\UserManagerBundle\Entity\Country;
use Spectasonic\Back\UserManagerBundle\Form\CountryType;

use Spectasonic\Back\UserManagerBundle\Form\CountryFilterType;

/**
 * Country controller.
 *
 */
class CountryController extends Controller
{
    /**
     * Lists all Country entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('SpectasonicBackUserManagerBundle:Country')->createQueryBuilder('e');
        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);

        list($countries, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        return $this->render('SpectasonicBackUserManagerBundle:Country:index.html.twig', array(
            'countries' => $countries,
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
        $filterForm = $this->createForm(new CountryFilterType());

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('CountryControllerFilter');
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
                $session->set('CountryControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('CountryControllerFilter')) {
                $filterData = $session->get('CountryControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm(new CountryFilterType(), $filterData);
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
            return $me->generateUrl('back_manager_country', array('page' => $page));
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
     * Displays a form to create a new Country entity.
     *
     */
    public function newAction(Request $request)
    {
    
        $country = new Country();
        $form   = $this->createForm(new CountryType(), $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($country);
            $em->flush();
            
            $editLink = $this->generateUrl('back_manager_country_edit', array('id' => $country->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New country was created successfully.</a>" );

            return $this->redirectToRoute('back_manager_country');
        }
        return $this->render('SpectasonicBackUserManagerBundle:Country:new.html.twig', array(
            'country' => $country,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a Country entity.
     *
     */
    public function showAction(Country $country)
    {
        $deleteForm = $this->createDeleteForm($country);
        return $this->render('SpectasonicBackUserManagerBundle:Country:show.html.twig', array(
            'country' => $country,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Country entity.
     *
     */
    public function editAction(Request $request, Country $country)
    {
        $deleteForm = $this->createDeleteForm($country);
        $editForm = $this->createForm(new CountryType(), $country);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($country);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('back_manager_country_edit', array('id' => $country->getId()));
        }
        return $this->render('SpectasonicBackUserManagerBundle:Country:edit.html.twig', array(
            'country' => $country,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Country entity.
     *
     */
    public function deleteAction(Request $request, Country $country)
    {
    
        $form = $this->createDeleteForm($country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($country);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Country was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Country');
        }
        
        return $this->redirectToRoute('back_manager_country');
    }
    
    /**
     * Creates a form to delete a Country entity.
     *
     * @param Country $country The Country entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Country $country)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('back_manager_country_delete', array('id' => $country->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Country by id
     *
     * @param mixed $id The entity id
     */
    public function deleteByIdAction(Country $country){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($country);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Country was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Country');
        }

        return $this->redirect($this->generateUrl('back_manager_country'));

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
                $repository = $em->getRepository('SpectasonicBackUserManagerBundle:Country');

                foreach ($ids as $id) {
                    $country = $repository->find($id);
                    $em->remove($country);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'countries was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the countries ');
            }
        }

        return $this->redirect($this->generateUrl('back_manager_country'));
    }
    

}
