<?php

namespace GestionBundle\Stats;

use Doctrine\ORM\EntityManager;
use GestionBundle\Stats\StatsQuery;

class StatsHandler
{
    private $dataColumn;
    private $dateRange;

    public function processData() {
        $qb = new StatsQuery($this->dateRange);
        $query = $qb->get($this->dataColumn);
        return $query;
    }

    /* setters */
    public function setDataColumn($dataColumn)
    {
        $this->dataColumn = $dataColumn;
        return $this;
    }
    public function setDateRange($dateRange)
    {
        $this->dateRange = $dateRange;
        return $this;
    }
    
    private function fetch($query)
    {
        $stmt = $this->em->getConnection()->prepare($query);
        $stmt->execute();
        return  $stmt->fetchAll();
    }
}