<?php 
namespace GestionBundle\Stats;

Class StatsQuery{
    
    private $dateFrom;
    private $dateTo;
    private $rangeDate;

    function __construct($daterange = null){
        $pattern = '#^[0-9]{4}/[0-9]{1,2}/[0-9]{1,2} - [0-9]{4}/[0-9]{1,2}/[0-9]{1,2}$#';
        $this->rangeDate = '';
        if (preg_match($pattern, $daterange)) {          
            $date = explode("-", $daterange);
            $this->dateFrom = $date[0];
            $this->dateTo = $date[1];
            $this->rangeDate .= (empty($this->dateFrom)) ? '' : "c.datePeremption >= '".$this->dateFrom."'" ;
            $this->rangeDate .= (empty($this->dateTo)) ? '' : " and c.datePeremption <= '".$this->dateTo."'" ;
        }
    }

    
    public function getStock()
    {
        return "SELECT c FROM GestionBundle:stock c WHERE {$this->rangeDate} ORDER BY c.id DESC";
    }

    public function get($value)
    {
        return $this->{'get'.ucfirst($value)}();
    }
}