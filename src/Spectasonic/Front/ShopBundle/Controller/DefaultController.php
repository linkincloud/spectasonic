<?php

namespace Spectasonic\Front\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class DefaultController extends Controller
{
    public function indexAction()
    {
        $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('SpectasonicBackShopManagerBundle:ShopProduct');

        $listProducts = $repository->getAllProducts();
        
        return $this->render('SpectasonicFrontShopBundle:Default:index.html.twig', array('listProducts' => $listProducts));
    }
    
    public function viewAction($slug) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('SpectasonicBackShopManagerBundle:ShopProduct');
        $product = $repository->myFindOneBySlug($slug);
        
        if (null === $product) {
            throw new NotFoundHttpException("Ce produit : " . $slug . " n'existe pas !");
        }

        return $this->render('SpectasonicFrontShopBundle:Default:view_post.html.twig', array(
                    'product' => $product,
        ));
    }
    
    public function viewCategoryAction($category) {
        $array = array('category' => $category);
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('SpectasonicBackShopManagerBundle:ShopProduct');
        $listProducts = $repository->getAllProductsWithCategory($array);
        
        return $this->render('SpectasonicFrontShopBundle:Default:index.html.twig', array(
                    'listProducts' => $listProducts,
        ));
    }
    
    /**
     * Affiche 3 derniers products à la page d'accueil
     */
    public function frontAction($limit = 3, $category = '') {
        //array('category' => 'services')
        if (!empty($category)) {
            $array = array('category' => $category);
        } else {
            $array = array('category' => '');
        }

        $listProducts = $this->getDoctrine()
                ->getManager()
                ->getRepository('SpectasonicBackShopManagerBundle:ShopProduct')
                ->getShopFront($limit, $array);

        return $this->render('SpectasonicFrontCoreBundle:Default:index-shop.html.twig', array(
                    'listProducts' => $listProducts
        ));
    }
}
