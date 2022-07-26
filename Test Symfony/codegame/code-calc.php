<?php

class Somme
{   
    function calc($array, $n1, $n2){
        for ($i = $n1; $i <= $n2  ; $i++) { 
            $sum += $array[$i];
            if($i == $n1)
                $titre = $array[$i];
            else
                $titre = $titre." + ".$array[$i];
        }
        echo $titre. " = ";
        echo $sum .PHP_EOL;
    }
    
}
$array = array(0,1,2,3,4,5,3);
$val = new Somme();
$val->calc($array, 0, 4);