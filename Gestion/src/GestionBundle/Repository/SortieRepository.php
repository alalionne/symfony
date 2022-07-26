<?php

namespace GestionBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * SortieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SortieRepository extends \Doctrine\ORM\EntityRepository
{	/* advanced search */
    public function search($searchParam) { 
        extract($searchParam);           
        $qb = $this->createQueryBuilder('s')
        	->leftJoin('s.produit', 'p')
            ->addSelect('p')
            ->leftJoin('s.destination', 'd')
            ->addSelect('d');

        if(!empty($keyword)){ 
            $qb->andWhere('s.code like :keyword or p.dosage like :keyword or p.dci like :keyword or s.quantite like :keyword or s.conditionnement like :keyword or s.dateSortie like :keyword or d.nom like :keyword')
                ->setParameter('keyword', '%'.$keyword.'%');
        }
        if(!empty($sortBy)){ 
            $sortBy = in_array($sortBy, array('code')) ? $sortBy : 'id';
            $sortDir = ($sortDir == 'DESC') ? 'DESC' : 'ASC';
            $qb->orderBy('s.' . $sortBy, $sortDir);
        }
        if(!empty($perPage)) $qb->setFirstResult(($page - 1) * $perPage)->setMaxResults($perPage);

       return new Paginator($qb->getQuery());
    }
}