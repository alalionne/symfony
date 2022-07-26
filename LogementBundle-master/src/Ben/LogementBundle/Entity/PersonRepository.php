<?php

namespace Ben\LogementBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Ben\LogementBundle\Entity\Person;

/**
 * PersonRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PersonRepository extends EntityRepository
{
    public function search($nombreParPage, $page, $keyword, $type, $status, $searchEntity, $logement, $orderList = null) { 
       $qb = $this->createQueryBuilder('p')
                ->leftJoin('p.logement', 'l')
                ->andWhere('l.id = :logement')
                ->setParameter('logement', $logement)
                ->leftJoin('p.etablissement', 'e')
                ->andWhere("concat(p.family_name, p.first_name) like :keyword or p.cin like :keyword or p.cne like :keyword")
                ->setParameter('keyword', '%'.$keyword.'%');
                 
        if($type !== 'all')
            $qb->andWhere('p.type = :type')->setParameter('type', $type);

        if ($orderList) {
            $qb->andWhere('p.id in (:orderList)')->setParameter('orderList', $orderList);
        }
        elseif($status !== 'all')
            $qb->andWhere('p.status = :status')->setParameter('status', $status);

        if($searchEntity){
            extract($searchEntity); 
            if($dossier !== '')
                $qb->andWhere('p.n_dossier = :dossier')->setParameter('dossier', $dossier);
            if($gender !== '')
                $qb->andWhere('p.gender = :gender')->setParameter('gender', $gender);
            if($city !== '')
                $qb->andWhere('p.city = :city')->setParameter('city', $city);
            if($exellence !== '')
                $qb->andWhere('p.exellence = :exellence')->setParameter('exellence', $exellence);
            if($parents_revenu !== '')
                $qb->andWhere('p.parents_revenu = :parents_revenu')->setParameter('parents_revenu', $parents_revenu);
            if($bro_sis_number !== '')
                $qb->andWhere('p.bro_sis_number = :bro_sis_number')->setParameter('bro_sis_number', $bro_sis_number);
            if($ancientete !== '')
                $qb->andWhere('p.ancientete = :ancientete')->setParameter('ancientete', $ancientete);
            if($etablissement !== '')
                $qb->andWhere('e.id = :etablissement')->setParameter('etablissement', $etablissement);
            if($sortBy !== ''){
                $sortBy = in_array($sortBy, array('n_dossier', 'family_name', 'status', 'type')) ? $sortBy : 'id';
                $sortDir = $sortDir == 'ASC' ? 'ASC' : 'DESC';
                $qb->orderBy('p.' . $sortBy, $sortDir);
            }
        }

        $qb->setFirstResult(($page - 1) * $nombreParPage)
        ->setMaxResults($nombreParPage);

       return new Paginator($qb->getQuery());
    }

    public function getList($logement, $university, $gender, $type, $status, $limit = null) {       
       $qb = $this->createQueryBuilder('p')
                ->leftJoin('p.logement', 'l')
                ->leftJoin('p.etablissement', 'e')
                ->addSelect('e')
                ->andWhere('l.id = :logement')
                ->setParameter('logement', $logement);
        if($status !== 'all')
            $qb->andWhere('p.status = :status')->setParameter('status', $status);
        if($type !== 'all')
            $qb->andWhere('p.type = :type')->setParameter('type', $type);
        if($gender !== 'all')
            $qb->andWhere('p.gender = :gender')->setParameter('gender', $gender);
        if(isset($university))
            $qb->andWhere('e.id = :university')->setParameter('university', $university);
        if(isset($limit))
            $qb->setMaxResults($limit)->orderBy('p.note', 'DESC');

       return $qb->getQuery()->getResult();
    }

    public function addOneYear($logement)
    {
        // reomve non resident
        $sql1 = "DELETE FROM reservation where person_id IN 
                (select id from person where status != 'résidant' and logement_id = $logement)";
        $sql2 = "DELETE FROM person where status != 'résidant' and logement_id = $logement";
        $sql2 = "UPDATE person set remarque = status where status != 'résidant' and logement_id = $logement";
        $sql22 = "UPDATE person set status = 'archive' where status != 'résidant' and logement_id = $logement";
        // disable resident users        
        $sql3 = "UPDATE person set status = 'suspendu', ancientete = ancientete + 1 where status = 'résidant' and logement_id = $logement";
        $sql4 = "UPDATE person set type = 'ancien' where type = 'nouveau' and status != 'archive' and logement_id = $logement";
        $sql5 = "UPDATE reservation 
                left join person on person.id = reservation.person_id
                set reservation.status = 'non valide'
                where person.logement_id = $logement";

        $stmt = $this->getEntityManager()->getConnection();
        $stmt->prepare($sql1)->execute();
        $stmt->prepare($sql2)->execute();
        $stmt->prepare($sql22)->execute();
        $stmt->prepare($sql3)->execute();
        $stmt->prepare($sql4)->execute();
        $stmt->prepare($sql5)->execute();
    }

    public function counter($logement, $status = 'all', $gender = 'all', $type='all') {
        $qb = $this->createQueryBuilder('p')
                ->select('COUNT(p)')
                ->leftJoin('p.logement', 'l')
                ->andWhere('l.id = :logement')
                ->setParameter('logement', $logement);
        if($status !== 'all')
            if($status === 'request') 
                $qb->andWhere("p.ancientete = 1 and p.status not in ('archive', 'suspendu', 'résidant') ");
                // $qb->andWhere('SUBSTRING(CURRENT_DATE(),1,10) = SUBSTRING(p.created,1,10) or p.status like :status')->setParameter('status', Person::$residentStatus);
            else $qb->andWhere('p.status like :status')->setParameter('status', $status);

        if($gender !== 'all') 
            $qb->andWhere('p.gender like :gender')->setParameter('gender', $gender);

        if($type !== 'all') 
            $qb->andWhere('p.type like :type')->setParameter('type', $type);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function statsByCity($logement) {
        $sql1 = "
        select * from
        (SELECT city, count(id) as 'all' FROM person where city is not NULL and status = 'résidant' and logement_id = $logement group by city order By city ASC) A
        left join (SELECT  city as city1, count(id) as 'allnew' FROM person where type = 'nouveau' and status = 'résidant' and logement_id = $logement group by city) B on B.city1 = A.city
        left join (SELECT  city as city2, count(id) as 'women' FROM person where gender = 'FILLE' and status = 'résidant' and logement_id = $logement group by city) C on C.city2 = A.city
        left join (SELECT  city as city3, count(id) as 'womennew' FROM person where gender = 'FILLE' and status = 'résidant' and type = 'nouveau' and logement_id = $logement group by city) D on D.city3 = A.city
        ";
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql1);
        $stmt->execute();
        return  $stmt->fetchAll();
    }

    public function statsByUniversity($logement) {
        $sql1 = "
        select * from
        (SELECT u.name, count(*) as 'all' FROM person left join university u on u.id = person.etablissement_id 
            where logement_id = $logement and status = 'résidant' group by u.name order By u.name ASC) A
        left join (SELECT  u.name as name1, count(*) as 'allnew' FROM person left join university u on u.id = person.etablissement_id 
            where person.type = 'nouveau' and status = 'résidant' and logement_id = $logement group by u.name) B on B.name1 = A.name
        left join (SELECT  u.name as name2, count(*) as 'women' FROM person left join university u on u.id = person.etablissement_id 
            where gender = 'FILLE' and status = 'résidant' and logement_id = $logement group by name) C on C.name2 = A.name
        left join (SELECT  u.name as name3, count(*) as 'womennew' FROM person left join university u on u.id = person.etablissement_id 
            where gender = 'FILLE' and status = 'résidant' and person.type = 'nouveau' and logement_id = $logement group by name) D on D.name3 = A.name
        left join (SELECT  u.name as name4, count(*) as 'womenforeign' FROM person left join university u on u.id = person.etablissement_id 
            where gender = 'FILLE' and status = 'résidant' and person.type = 'etranger' and logement_id = $logement group by name) E on E.name4 = A.name
        left join (SELECT  u.name as name5, count(*) as 'allforeign' FROM person left join university u on u.id = person.etablissement_id 
            where person.type = 'etranger' and status = 'résidant' and logement_id = $logement group by u.name) F on F.name5 = A.name
        ";
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql1);
        $stmt->execute();
        return  $stmt->fetchAll();
    }

    public function statsByDiplome($logement) {
        $sql1 = "
        select * from
        (select diplome, niveau_etude, count(*) as 'all' FROM person where logement_id = $logement and status = 'résidant' group by diplome, niveau_etude order By diplome ASC) A
        left join (select diplome as diplome1, niveau_etude as niveau_etude1, count(*) as 'allnew' FROM person where type = 'nouveau' and status = 'résidant' and logement_id = $logement group by diplome, niveau_etude) B on B.diplome1 = A.diplome and B.niveau_etude1 = A.niveau_etude
        left join (select diplome as diplome2, niveau_etude as niveau_etude2, count(*) as 'women' FROM person where gender = 'FILLE' and status = 'résidant' and logement_id = $logement group by diplome, niveau_etude) C on C.diplome2 = A.diplome and C.niveau_etude2 = A.niveau_etude
        left join (select diplome as diplome3, niveau_etude as niveau_etude3, count(*) as 'womennew' FROM person where gender = 'FILLE' and status = 'résidant' and type = 'nouveau' and logement_id = $logement group by diplome, niveau_etude) D on D.diplome3 = A.diplome and D.niveau_etude3 = A.niveau_etude
        left join (select diplome as diplome4, niveau_etude as niveau_etude4, count(*) as 'womenforeign' FROM person where gender = 'FILLE' and status = 'résidant' and person.type = 'etranger' and logement_id = $logement group by diplome, niveau_etude) E on E.diplome4 = A.diplome and E.niveau_etude4 = A.niveau_etude
        left join (select diplome as diplome5, niveau_etude as niveau_etude5, count(*) as 'allforeign' FROM person where person.type = 'etranger' and status = 'résidant' and logement_id = $logement group by diplome, niveau_etude) F on F.diplome5 = A.diplome and F.niveau_etude5 = A.niveau_etude 
        ";
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql1);
        $stmt->execute();
        return  $stmt->fetchAll();
    }

    public function statsByAge($logement) {
        $sql1 = "
        select * from
        (SELECT ROUND(DATEDIFF(Cast(NOW() as Date), Cast(bird_day as Date)) / 365, 0) as age, count(*) as 'all' FROM person where logement_id = $logement and status = 'résidant' group by age order By age ASC) A
        left join (SELECT  ROUND(DATEDIFF(Cast(NOW() as Date), Cast(bird_day as Date)) / 365, 0) as age1, count(*) as 'allnew' FROM person where type = 'nouveau' and status = 'résidant' and logement_id = $logement group by age1) B on B.age1 = A.age
        left join (SELECT  ROUND(DATEDIFF(Cast(NOW() as Date), Cast(bird_day as Date)) / 365, 0) as age2, count(*) as 'women' FROM person where gender = 'FILLE' and status = 'résidant' and logement_id = $logement group by age2) C on C.age2 = A.age
        left join (SELECT  ROUND(DATEDIFF(Cast(NOW() as Date), Cast(bird_day as Date)) / 365, 0) as age3, count(*) as 'womennew' FROM person where gender = 'FILLE' and status = 'résidant' and type = 'nouveau' and logement_id = $logement group by age3) D on D.age3 = A.age
        left join (SELECT  ROUND(DATEDIFF(Cast(NOW() as Date), Cast(bird_day as Date)) / 365, 0) as age4, count(*) as 'womenforeign' FROM person where gender = 'FILLE' and status = 'résidant' and person.type = 'etranger' and logement_id = $logement group by age4) E on E.age4 = A.age
        left join (SELECT  ROUND(DATEDIFF(Cast(NOW() as Date), Cast(bird_day as Date)) / 365, 0) as age5, count(*) as 'allforeign' FROM person where person.type = 'etranger' and status = 'résidant' and logement_id = $logement group by age5) F on F.age5 = A.age
        ";
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql1);
        $stmt->execute();
        return  $stmt->fetchAll();
    }

    public function statsByAncientete($logement) {
        $sql1 = "
        select * from
        (SELECT ancientete, count(*) as 'all' FROM person where logement_id = $logement and status = 'résidant' group by ancientete order By ancientete ASC) A
        left join (SELECT  ancientete as ancientete2, count(*) as 'women' FROM person where gender = 'FILLE' and status = 'résidant' and logement_id = $logement group by ancientete) C on C.ancientete2 = A.ancientete
        left join (SELECT  ancientete as ancientete4, count(*) as 'womenforeign' FROM person where gender = 'FILLE' and status = 'résidant' and person.type = 'etranger' and logement_id = $logement group by ancientete) E on E.ancientete4 = A.ancientete
        left join (SELECT  ancientete as ancientete5, count(*) as 'allforeign' FROM person where person.type = 'etranger' and status = 'résidant' and logement_id = $logement group by ancientete) F on F.ancientete5 = A.ancientete
        ";
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql1);
        $stmt->execute();
        return  $stmt->fetchAll();
    }
}