<?php

class change
{
   public $coin2 = 0;
   public $bill5 = 0;
   public $bill10 = 0;
	
}
class Solution{
   public function optimalChange($s) 
   {
      $c = new change();
         
      if($s >= 10) 
      {
         $c->bill10 = intval($s/10);
         if($s % 10 >= 5) 
         {
            $c->bill5 = intval(($s %10 ) / 5);
               
            if(($s % 10) %5 >= 2)
            {
               $c->coin2 = intval((( $s%10 ) %5 ) / 2);
            }     
         }else if($s % 10 >= 2)
         {
            $c->coin2= intval(($s % 10)/2);
         }
      }else if($s>=5 && $s%2 != 0)
      {
         $c->bill5 = intval($s/5);
         if($s%5>=2){
            $c->coin2= intval(($s%5)/2);
         }
      }else if($s >=2)
      {
         $c->coin2 = intval($s/2);
      }
      return $c;
   }
}
$opti = new Solution();
$s = 14;
$m = $opti->optimalChange($s);
var_dump($m);

echo "Coin(s) 2€: " . $m->coin2 . "\n";
echo "Bill(s) 5€: " . $m->bill5 . "\n";
echo "Bill(s) 10€: " . $m->bill10 . "\n";

$result = $m->coin2*2 + $m->bill5*5 + $m->bill10*10;
echo "\nChange given = " . $result;


<?php
// do not modify change
class Change
{
    public $coin2 = 0;
    public $bill5 = 0;
    public $bill10 = 0;
}

// do not modify the signature of this​​​​​​‌​​‌​‌‌​‌‌‌‌​​‌‌‌‌‌​​​‌​​ function
function optimalChange($s)
{
    $c = new change();

    if($s >= 10)
    {
        $c->bill10 = intval($s/10);
        if($s % 10 >= 5 && ($s % 10)%2 != 0)
        {
            $c->bill5 = intval(($s %10 ) / 5);

            if(($s % 10) %5 >= 2)
            {
                $c->coin2 = intval((( $s%10 ) %5 ) / 2);
            }
        }else if($s % 10 >= 2 &&  ($s % 10)%2 == 0)
        {
            $c->coin2= intval(($s % 10)/2);
        }else if($s % 10 != 0){
            $c->coin2 = NULL;
            $c->bill5 = NULL;
            $c->bill10 = NULL;
        }
    }
    else if($s>=5 && $s%2 != 0)
    {
        $c->bill5 = intval($s/5);
        if($s%5>=2){
            $c->coin2= intval(($s%5)/2);
        }
    }else if($s >=2 && $s%2 == 0 )
    {
       $c->coin2 = intval($s/2);
    }else{
        $c->coin2 = NULL;
        $c->bill5 = NULL;
        $c->bill10 = NULL;
    }

    return $c;

}
?>
