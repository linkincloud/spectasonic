<?php

namespace Spectasonic\Back\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use Spectasonic\Back\BlogBundle\Entity\BlogCategory;
use Spectasonic\Back\BlogBundle\Form\BlogCategoryType;

use Spectasonic\Back\BlogBundle\Form\BlogCategoryFilterType;

/**
 * BlogCategory controller.
 *
 */
class BlogCategoryController extends Controller
{
    /**
     * Lists all BlogCategory entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('SpectasonicBackBlogBundle:BlogCategory')->createQueryBuilder('e');
        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);

        list($blogCategories, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        return $this->render('SpectasonicBackBlogBundle:Category:index.html.twig', array(
            'blogCategories' => $blogCategories,
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
        $filterForm = $this->createForm(new BlogCategoryFilterType());

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('BlogCategoryControllerFilter');
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
                $session->set('BlogCategoryControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('BlogCategoryControllerFilter')) {
                $filterData = $session->get('BlogCategoryControllerFilter');
                $filterForm = $this->createForm(new BlogCategoryFilterType(), $filterData);
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
            return $me->generateUrl('spectasonic_back_blog_category', array('page' => $page));
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
     * Displays a form to create a new BlogCategory entity.
     *
     */
    public function newAction(Request $request)
    {
    
        $blogCategory = new BlogCategory();
        $form   = $this->createForm(new BlogCategoryType(), $blogCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($blogCategory);
            $em->flush();

            return $this->redirectToRoute('spectasonic_back_blog_category_show', array('id' => $blogCategory->getId()));
        }
        return $this->render('SpectasonicBackBlogBundle:Category:new.html.twig', array(
            'blogCategory' => $blogCategory,
            'form'   => $form->createView(),
        ));
    }
    
    

    
    /**
     * Finds and displays a BlogCategory entity.
     *
     */
    public function showAction(BlogCategory $blogCategory)
    {
        $deleteForm = $this->createDeleteForm($blogCategory);
        return $this->render('SpectasonicBackBlogBundle:Category:show.html.twig', array(
            'blogCategory' => $blogCategory,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing BlogCategory entity.
     *
     */
    public function editAction(Request $request, BlogCategory $blogCategory)
    {
        $deleteForm = $this->createDeleteForm($blogCategory);
        $editForm = $this->createForm(new BlogCategoryType(), $blogCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($blogCategory);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('spectasonic_back_blog_category_edit', array('id' => $blogCategory->getId()));
        }
        return $this->render('SpectasonicBackBlogBundle:Category:edit.html.twig', array(
            'blogCategory' => $blogCategory,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a BlogCategory entity.
     *
     */
    public function deleteAction(Request $request, BlogCategory $blogCategory)
    {
    
        $form = $this->createDeleteForm($blogCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($blogCategory);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }
        
        return $this->redirectToRoute('spectasonic_back_blog_category');
    }
    
    /**
     * Creates a form to delete a BlogCategory entity.
     *
     * @param BlogCategory $blogCategory The BlogCategory entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(BlogCategory $blogCategory)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('spectasonic_back_blog_category_delete', array('id' => $blogCategory->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete BlogCategory by id
     *
     * @param mixed $id The entity id
     */
    public function deleteById($id){

        $em = $this->getDoctrine()->getManager();
        $blogCategory = $em->getRepository('SpectasonicBackBlogBundle:BlogCategory')->find($id);
        
        if (!$blogCategory) {
            throw $this->createNotFoundException('Unable to find BlogCategory entity.');
        }
        
        try {
            $em->remove($blogCategory);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('spectasonic_back_blog_category'));

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
                $repository = $em->getRepository('SpectasonicBackBlogBundle:BlogCategory');

                foreach ($ids as $id) {
                    $blogCategory = $repository->find($id);
                    $em->remove($blogCategory);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'blogCategories was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the blogCategories ');
            }
        }

        return $this->redirect($this->generateUrl('spectasonic_back_blog_category'));
    }
    
    
}
