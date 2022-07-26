<?php
namespace AppBundle\Service;

class ArrandMessage{

    public function showMessage()
    {
        $message = array(
            'Nous testons notre framework sf3', 
            'Primier test après 3 ans d\'absence',
            'Bon il nous a fallu être booster par quequ\'un'
        );
        
        $index = array_rand($message);
        dump($index);
        dump($message[$index]);die();

        return $message[$index];
    }
}