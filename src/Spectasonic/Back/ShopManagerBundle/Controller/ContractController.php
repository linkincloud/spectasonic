<?php

namespace Spectasonic\Back\ShopManagerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use Spectasonic\Back\ShopManagerBundle\Entity\Contract;
use Spectasonic\Back\ShopManagerBundle\Form\ContractType;

use Spectasonic\Back\ShopManagerBundle\Form\ContractFilterType;

/**
 * Contract controller.
 *
 */
class ContractController extends Controller
{
    /**
     * Lists all Contract entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('SpectasonicBackShopManagerBundle:Contract')->createQueryBuilder('e');
        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);

        list($contracts, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        return $this->render('SpectasonicBackShopManagerBundle:Contract:index.html.twig', array(
            'contracts' => $contracts,
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
        $filterForm = $this->createForm(new ContractFilterType());

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('ContractControllerFilter');
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
                $session->set('ContractControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('ContractControllerFilter')) {
                $filterData = $session->get('ContractControllerFilter');
                $filterForm = $this->createForm(new ContractFilterType(), $filterData);
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
            return $me->generateUrl('spectasonic_back_shop_manager_list_contract', array('page' => $page));
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
     * Displays a form to create a new Contract entity.
     *
     */
    public function newAction(Request $request)
    {
    
        $contract = new Contract();
        $form   = $this->createForm(new ContractType(), $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contract);
            $em->flush();

            return $this->redirectToRoute('spectasonic_back_shop_manager_show_contract', array('id' => $contract->getId()));
        }
        return $this->render('SpectasonicBackShopManagerBundle:Contract:new.html.twig', array(
            'contract' => $contract,
            'form'   => $form->createView(),
        ));
    }
    
    

    
    /**
     * Finds and displays a Contract entity.
     *
     */
    public function showAction(Contract $contract)
    {
        $deleteForm = $this->createDeleteForm($contract);
        return $this->render('SpectasonicBackShopManagerBundle:Contract:show.html.twig', array(
            'contract' => $contract,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Contract entity.
     *
     */
    public function editAction(Request $request, Contract $contract)
    {
        $deleteForm = $this->createDeleteForm($contract);
        $editForm = $this->createForm(new ContractType(), $contract);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contract);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('spectasonic_back_shop_manager_edit_contract', array('id' => $contract->getId()));
        }
        return $this->render('SpectasonicBackShopManagerBundle:Contract:edit.html.twig', array(
            'contract' => $contract,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Contract entity.
     *
     */
    public function deleteAction(Request $request, Contract $contract)
    {
    
        $form = $this->createDeleteForm($contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contract);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }
        
        return $this->redirectToRoute('spectasonic_back_shop_manager_list_contract');
    }
    
    /**
     * Creates a form to delete a Contract entity.
     *
     * @param Contract $contract The Contract entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Contract $contract)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('spectasonic_back_shop_manager_delete_contract', array('id' => $contract->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Contract by id
     *
     * @param mixed $id The entity id
     */
    public function deleteByIdAction($id){
        $em = $this->getDoctrine()->getManager();
        $contract = $em->getRepository('SpectasonicBackShopManagerBundle:Contract')->find($id);
        
        if (!$contract) {
            throw $this->createNotFoundException('Unable to find Contract entity.');
        }
        
        try {
            $em->remove($contract);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('spectasonic_back_shop_manager_list_contract'));

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
                $repository = $em->getRepository('SpectasonicBackShopManagerBundle:Contract');

                foreach ($ids as $id) {
                    $contract = $repository->find($id);
                    $em->remove($contract);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'contracts was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the contracts ');
            }
        }

        return $this->redirect($this->generateUrl('spectasonic_back_shop_manager_list_contract'));
    }
    
    
}
