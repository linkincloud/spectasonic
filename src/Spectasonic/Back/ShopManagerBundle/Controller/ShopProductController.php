<?php

namespace Spectasonic\Back\ShopManagerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use Spectasonic\Back\ShopManagerBundle\Entity\ShopProduct;
use Spectasonic\Back\ShopManagerBundle\Form\ShopProductType;

use Spectasonic\Back\ShopManagerBundle\Form\ShopProductFilterType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * ShopProduct controller.
 *
 */
class ShopProductController extends Controller
{
    /**
     * Lists all ShopProduct entities.
     *
     */
    public function indexAction(Request $request)
    {

        if (!$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins');
        }

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('SpectasonicBackShopManagerBundle:ShopProduct')->createQueryBuilder('e');
        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);

        list($shopProducts, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        return $this->render('SpectasonicBackShopManagerBundle:Product:index.html.twig', array(
            'shopProducts' => $shopProducts,
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
        $filterForm = $this->createForm(new ShopProductFilterType());

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('ShopProductControllerFilter');
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
                $session->set('ShopProductControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('ShopProductControllerFilter')) {
                $filterData = $session->get('ShopProductControllerFilter');
                $filterForm = $this->createForm(new ShopProductFilterType(), $filterData);
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
            return $me->generateUrl('spectasonic_back_shop_manager_list_product', array('page' => $page));
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
     * Displays a form to create a new ShopProduct entity.
     *
     */
    public function newAction(Request $request)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins');
        }
    
        $shopProduct = new ShopProduct();
        $form   = $this->createForm(new ShopProductType(), $shopProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($shopProduct);
            $em->flush();

            return $this->redirectToRoute('spectasonic_back_shop_manager_show_product', array('id' => $shopProduct->getId()));
        }
        return $this->render('SpectasonicBackShopManagerBundle:Product:new.html.twig', array(
            'shopProduct' => $shopProduct,
            'form'   => $form->createView(),
        ));
    }
    
    

    
    /**
     * Finds and displays a ShopProduct entity.
     *
     */
    public function showAction(ShopProduct $shopProduct)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins');
        }
        $deleteForm = $this->createDeleteForm($shopProduct);
        return $this->render('SpectasonicBackShopManagerBundle:Product:show.html.twig', array(
            'shopProduct' => $shopProduct,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing ShopProduct entity.
     *
     */
    public function editAction(Request $request, ShopProduct $shopProduct)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins');
        }
        $deleteForm = $this->createDeleteForm($shopProduct);
        $editForm = $this->createForm(new ShopProductType(), $shopProduct);
        $editForm->handleRequest($request);
         
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($shopProduct);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('spectasonic_back_shop_manager_show_product', array('id' => $shopProduct->getId()));
        }
        return $this->render('SpectasonicBackShopManagerBundle:Product:edit.html.twig', array(
            'shopProduct' => $shopProduct,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a ShopProduct entity.
     *
     */
    public function deleteAction(Request $request, ShopProduct $shopProduct)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins');
        }
    
        $form = $this->createDeleteForm($shopProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($shopProduct);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }
        
        return $this->redirectToRoute('spectasonic_back_shop_manager_list_product');
    }
    
    /**
     * Creates a form to delete a ShopProduct entity.
     *
     * @param ShopProduct $shopProduct The ShopProduct entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ShopProduct $shopProduct)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins');
        }
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('spectasonic_back_shop_manager_delete_product', array('id' => $shopProduct->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete ShopProduct by id
     *
     * @param mixed $id The entity id
     */
    public function deleteById($id){
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux admins');
        }
        $em = $this->getDoctrine()->getManager();
        $shopProduct = $em->getRepository('SpectasonicBackShopManagerBundle:ShopProduct')->find($id);
        
        if (!$shopProduct) {
            throw $this->createNotFoundException('Unable to find ShopProduct entity.');
        }
        
        try {
            $em->remove($shopProduct);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('spectasonic_back_shop_manager_list_product'));

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
                $repository = $em->getRepository('SpectasonicBackShopManagerBundle:ShopProduct');

                foreach ($ids as $id) {
                    $shopProduct = $repository->find($id);
                    $em->remove($shopProduct);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'shopProducts was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the shopProducts ');
            }
        }

        return $this->redirect($this->generateUrl('spectasonic_back_shop_manager_list_product'));
    }
    
    
}
