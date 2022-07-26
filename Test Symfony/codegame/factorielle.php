<?php

class Fact
{   
    function factorielle($n){
        if($n == 0)
            return 1;
        else
            return $n * $this->factorielle($n-1);
    }
    
}
$n = 10; 
$val = new Fact();
echo $val->factorielle($n) .PHP_EOL;