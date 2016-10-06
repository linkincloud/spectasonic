<?php

namespace Spectasonic\Back\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class BlogRepository extends EntityRepository {
    /*
     * Pour le front uniquement 
     */

    public function getAllPosts() {

        $qb = $this->createQueryBuilder('p');

        $qb
                /*->join('p.mainimage', 'm')
                ->addSelect('m')*/
                ->join('p.categories', 'c')
                ->addSelect('c')
                ->where('p.page = false')
                ->andWhere('p.published = true')
                ->orderBy('p.date', 'DESC');

        return $qb->getQuery()->getResult();

        /* return $this
          ->createQueryBuilder('p')
          ->getQuery()
          ->getResult(); */
    }

    /*
     * Pour afficher dans le front
     * 
     */

    public function myFindOne($id) {
        $qb = $this->createQueryBuilder('p');

        $qb
               /* ->join('p.mainimage', 'm')
                ->addSelect('m')*/
                ->join('p.categories', 'c')
                ->addSelect('c')
                ->where('p.id = :id')
                ->setParameter('id', $id);

        return $qb->getQuery()->getSingleResult();
    }

    /*
     * Pour afficher dans le front
     * 
     */
    public function myFindOneBySlug($slug) {
        $qb = $this->createQueryBuilder('p');

        $qb
                /*->join('p.mainimage', 'm')
                ->addSelect('m')*/
                ->join('p.categories', 'c')
                ->addSelect('c')
                ->where('p.slug = :slug')
                ->setParameter('slug', $slug);

        return $qb->getQuery()->getSingleResult();
    }

    /*
     * Utilisé pour le menuservices en menu en front
     */

    public function getAllPostsWithCategory(array $categoryNames) {
        $qb = $this->createQueryBuilder('p');

        $qb
               /* ->join('p.mainimage', 'm')
                ->addSelect('m')*/
                ->join('p.categories', 'c')
                ->addSelect('c')

                ->andWhere('p.published = true');

        $qb->andWhere($qb->expr()->in('c.name', $categoryNames));

        return $qb
                        ->getQuery()
                        ->getResult();
    }
    
    public function getPostsWithCategory(array $categoryNames) {
        $qb = $this->createQueryBuilder('p');

        $qb

                ->join('p.categories', 'c')
                ->addSelect('c')
                ->where('p.page = true')
                ->andWhere('p.published = true');

        $qb->andWhere($qb->expr()->in('c.name', $categoryNames));

        return $qb
                        ->getQuery()
                        ->getResult();
    }

    /*
     * Utilisé pour le front pour la page index
     */

    public function getBlogFront($limit = 3, $page = 0, array $categoryNames = []) {
        $qb = $this->createQueryBuilder('p');

        if ($categoryNames['category'] != '') {
            $qb
                    ->join('p.categories', 'c')
                    ->addSelect('c')
                    ->where('p.page = :page')
                    ->setParameter('page', $page)
                    ->andWhere('p.published = true')
                    ->andWhere($qb->expr()->in('c.name', $categoryNames))
                    ->orderBy('p.date', 'DESC')
                    ->setMaxResults($limit);
        } else {
            $qb
                    ->join('p.categories', 'c')
                    ->addSelect('c')
                    ->where('p.page = :page')
                    ->setParameter('page', $page)
                    ->andWhere('p.published = true')
                    ->orderBy('p.date', 'DESC')
                    ->setMaxResults($limit);
        }

        return $qb
                        ->getQuery()
                        ->getResult();
    }

}
