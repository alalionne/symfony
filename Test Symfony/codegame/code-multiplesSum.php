<?php

class Multiple
{
    function computeMultiplesSum($n){
        $tab = [];
        for($i=0; $i <$n; $i++){
            if($i % 3 == 0 || $i % 5 == 0 || $i % 9 == 0){
                $sum += $i; 
                array_push($tab,$i);
            }
        }
        
        print_r($tab) .PHP_EOL;
        echo $sum. PHP_EOL;
    }
}
$n= 199;
$val = new Multiple();
$val->computeMultiplesSum($n);