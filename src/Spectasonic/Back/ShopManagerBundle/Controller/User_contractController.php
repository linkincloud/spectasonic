<?php

namespace Spectasonic\Back\ShopManagerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use Spectasonic\Back\ShopManagerBundle\Entity\User_contract;
use Spectasonic\Back\ShopManagerBundle\Form\User_contractType;

use Spectasonic\Back\ShopManagerBundle\Form\User_contractFilterType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * User_contract controller.
 *
 */
class User_contractController extends Controller
{
    /**
     * Lists all User_contract entities.
     *
     */
    public function indexAction(Request $request)
    {
        if ((!$this->get('security.context')->isGranted('ROLE_ADMIN'))
            OR
            (!$this->get('security.context')->isGranted('ROLE_VENDEUR'))) {
        }else{
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins et aux vendeurs');
        }
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('SpectasonicBackShopManagerBundle:User_contract')->createQueryBuilder('e');
        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);

        list($user_contracts, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        return $this->render('SpectasonicBackShopManagerBundle:User_contract:index.html.twig', array(
            'user_contracts' => $user_contracts,
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
        $filterForm = $this->createForm(new User_contractFilterType());

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('User_contractControllerFilter');
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
                $session->set('User_contractControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('User_contractControllerFilter')) {
                $filterData = $session->get('User_contractControllerFilter');
                $filterForm = $this->createForm(new User_contractFilterType(), $filterData);
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
            return $me->generateUrl('spectasonic_back_shop_manager_list_user_contract', array('page' => $page));
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
     * Displays a form to create a new User_contract entity.
     *
     */
    public function newAction(Request $request)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins');
        }
    
        $user_contract = new User_contract();
        $form = $this->createForm(new User_contractType(), $user_contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user_contract);
            $em->flush();

            return $this->redirectToRoute('spectasonic_back_shop_manager_show_user_contract', array('id' => $user_contract->getId()));
        }
        return $this->render('SpectasonicBackShopManagerBundle:User_contract:new.html.twig', array(
            'user_contract' => $user_contract,
            'form'   => $form->createView(),
        ));
    }
    
    

    
    /**
     * Finds and displays a User_contract entity.
     *
     */
    public function showAction(User_contract $user_contract)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins');
        }
        $deleteForm = $this->createDeleteForm($user_contract);
        return $this->render('SpectasonicBackShopManagerBundle:User_contract:show.html.twig', array(
            'user_contract' => $user_contract,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing User_contract entity.
     *
     */
    public function editAction(Request $request, User_contract $user_contract)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins');
        }
        $deleteForm = $this->createDeleteForm($user_contract);
        $editForm = $this->createForm(new User_contractType(), $user_contract);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user_contract);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('spectasonic_back_shop_manager_show_user_contract', array('id' => $user_contract->getId()));
        }
        return $this->render('SpectasonicBackShopManagerBundle:User_contract:edit.html.twig', array(
            'user_contract' => $user_contract,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a User_contract entity.
     *
     */
    public function deleteAction(Request $request, User_contract $user_contract)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins');
        }
    
        $form = $this->createDeleteForm($user_contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user_contract);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }
        
        return $this->redirectToRoute('spectasonic_back_shop_manager_list_user_contract');
    }
    
    /**
     * Creates a form to delete a User_contract entity.
     *
     * @param User_contract $user_contract The User_contract entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User_contract $user_contract)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins');
        }
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('spectasonic_back_shop_manager_delete_user_contract', array('id' => $user_contract->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete User_contract by id
     *
     * @param mixed $id The entity id
     */
    public function deleteByIdAction($id){
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins');
        }
        $em = $this->getDoctrine()->getManager();
        $user_contract = $em->getRepository('SpectasonicBackShopManagerBundle:User_contract')->find($id);
        
        if (!$user_contract) {
            throw $this->createNotFoundException('Unable to find User_contract entity.');
        }
        
        try {
            $em->remove($user_contract);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('spectasonic_back_shop_manager_list_user_contract'));

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
                $repository = $em->getRepository('SpectasonicBackShopManagerBundle:User_contract');

                foreach ($ids as $id) {
                    $user_contract = $repository->find($id);
                    $em->remove($user_contract);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'user_contracts was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the user_contracts ');
            }
        }

        return $this->redirect($this->generateUrl('spectasonic_back_shop_manager_list_user_contract'));
    }
    
    
}
