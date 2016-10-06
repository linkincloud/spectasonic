<?php

namespace Spectasonic\Front\NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller {

    public function indexAction() {
        $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('SpectasonicBackBlogBundle:Blog');

        $listPosts = $repository->getAllPosts();

        return $this->render('SpectasonicFrontNewsBundle:Default:index.html.twig', array('listPosts' => $listPosts));
    }

    public function viewAction($slug) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('SpectasonicBackBlogBundle:Blog');
        $post = $repository->myFindOneBySlug($slug);

        if (null === $post) {
            throw new NotFoundHttpException("Ce post : " . $slug . " n'existe pas !");
        }

        return $this->render('SpectasonicFrontNewsBundle:Default:view_post.html.twig', array(
                    'post' => $post,
        ));
    }

    /**
     * 
     * @param type $category
     * @return type
     * @throws NotFoundHttpException
     */
    public function viewCategoryAction($category) {
        $array = array('category' => $category);
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('SpectasonicBackBlogBundle:Blog');
        $listPosts = $repository->getAllPostsWithCategory($array);

        return $this->render('SpectasonicFrontNewsBundle:Default:index.html.twig', array(
                    'listPosts' => $listPosts,
        ));
    }

    public function menuservicesAction() {
        $listPosts = $this->getDoctrine()
                ->getManager()
                ->getRepository('SpectasonicBackBlogBundle:Blog')
                ->getPostsWithCategory(array('category' => 'services'));

        return $this->render('SpectasonicFrontNewsBundle:Default:menuservices.html.twig', array(
                    'listPosts' => $listPosts
        ));
    }

    public function menuvaluesAction() {
        $listPosts = $this->getDoctrine()
                ->getManager()
                ->getRepository('SpectasonicBackBlogBundle:Blog')
                ->getPostsWithCategory(array('category' => 'values'));

        return $this->render('SpectasonicFrontNewsBundle:Default:menuvalues.html.twig', array(
                    'listPosts' => $listPosts
        ));
    }

    /**
     * Affiche 3 pages (1) ou post (0) à la page d'accueil

     */
    public function frontAction($limit = 3, $page = 0, $category = '') {
        //array('category' => 'services')
        if (!empty($category)) {
            $array = array('category' => $category);
        } else {
            $array = array('category' => '');
        }

        $listPosts = $this->getDoctrine()
                ->getManager()
                ->getRepository('SpectasonicBackBlogBundle:Blog')
                ->getBlogFront($limit, $page, $array);

        return $this->render('SpectasonicFrontCoreBundle:Default:index-blog.html.twig', array(
                    'listPosts' => $listPosts
        ));
    }

}
