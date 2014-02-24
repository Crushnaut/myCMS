<?php

namespace Phil\CMSBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends EntityRepository
{
    public function getVisibleCategories()
    {
        $qb = $this->createQueryBuilder('c')
                   ->select('c')
                   ->where('c.visible = :visibility')
                   ->addOrderBy('c.menuorder')
                   ->setParameter('visibility', 1);

        return $qb->getQuery()
                  ->getResult();
    }
}
