<?php

namespace Phil\CMSBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PageRepository extends EntityRepository
{
    public function getPagesFromCategory($categoryId)
    {
        $qb = $this->createQueryBuilder('p')
                   ->select('p')
                   ->where('p.visible = :visibility')
                   ->andWhere('p.category = :catid')
                   ->addOrderBy('p.created')
                   ->setParameter('visibility', 1)
                   ->setParameter('catid', $categoryId);

        return $qb->getQuery()
                  ->getResult();
    }
}
