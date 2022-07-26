<?php

namespace GestionBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * CategorieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategorieRepository extends \Doctrine\ORM\EntityRepository
{	
    /* advanced search */
    public function search($searchParam) {
        extract($searchParam);         
        $qb = $this->createQueryBuilder('p'); 

        if(!empty($keyword))
            $qb->andWhere('p.nom like :keyword')
                ->setParameter('keyword', '%'.$keyword.'%');
        if(!empty($sortBy)){ 
            $sortBy = in_array($sortBy, array('nom')) ? $sortBy : 'id';
            $sortDir = ($sortDir == 'DESC') ? 'DESC' : 'ASC';
            $qb->orderBy('p.' . $sortBy, $sortDir);
        }
        if(!empty($perPage)) $qb->setFirstResult(($page - 1) * $perPage)->setMaxResults($perPage);
       return new Paginator($qb->getQuery());
    }
}
