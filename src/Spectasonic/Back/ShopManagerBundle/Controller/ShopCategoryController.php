<?php

namespace Spectasonic\Back\ShopManagerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Spectasonic\Back\ShopManagerBundle\Entity\ShopCategory;
use Spectasonic\Back\ShopManagerBundle\Form\ShopCategoryType;

/**
 * ShopCategory controller.
 *
 */
class ShopCategoryController extends Controller
{
    /**
     * Lists all ShopCategory entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $shopCategories = $em->getRepository('SpectasonicBackShopManagerBundle:ShopCategory')->findAll();

        return $this->render('SpectasonicBackShopManagerBundle:Category:index.html.twig', array(
            'shopCategories' => $shopCategories,
        ));
    }

    /**
     * Creates a new ShopCategory entity.
     *
     *
     */
    public function newAction(Request $request)
    {
        $shopCategory = new ShopCategory();
        $form = $this->createForm(new ShopCategoryType(), $shopCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($shopCategory);
            $em->flush();

            return $this->redirectToRoute('spectasonic_back_shop_manager_list_category');
        }

        return $this->render('SpectasonicBackShopManagerBundle:Category:add.html.twig', array(
            'shopCategory' => $shopCategory,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ShopCategory entity.
     *
     *
     */
    public function showAction(ShopCategory $shopCategory)
    {
        $deleteForm = $this->createDeleteForm($shopCategory);

        return $this->render('SpectasonicBackShopManagerBundle:Category:show.html.twig', array(
            'shopCategory' => $shopCategory,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ShopCategory entity.
     *
     *
     */
    public function editAction(Request $request, ShopCategory $shopCategory)
    {
        $deleteForm = $this->createDeleteForm($shopCategory);
        $editForm = $this->createForm(new ShopCategoryType(), $shopCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($shopCategory);
            $em->flush();

            return $this->redirectToRoute('spectasonic_back_shop_manager_list_category');
        }

        return $this->render('SpectasonicBackShopManagerBundle:Category:edit.html.twig', array(
            'shopCategory' => $shopCategory,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ShopCategory entity.
     */
    public function deleteAction(Request $request, ShopCategory $shopCategory)
    {
        $form = $this->createDeleteForm($shopCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
   
            $em = $this->getDoctrine()->getManager();
            $em->remove($shopCategory);
            $em->flush();
        }

        return $this->redirectToRoute('spectasonic_back_shop_manager_list_category');
    }

    /**
     * Creates a form to delete a ShopCategory entity.
     *
     */
    private function createDeleteForm(ShopCategory $shopCategory)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('spectasonic_back_shop_manager_delete_category', array('id' => $shopCategory->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
