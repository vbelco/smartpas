<?php

/*
 * Dochadzka jedneho dna
 */

namespace App\Model;

use Nette;

class DochadzkaJednehoDna extends Nette\Object{
    /**
     * @var Nette\Database\Context
     */
    private $database;
    
    public $id; //id zaznamu v databaze
    public $den; 
    public $prichod;
    public $odchod;
    public $prichod_upravene;
    public $odchod_upravene;
    public $prichod_historia;
    public $odchod_historia;
    public $poznamka;
    
    function __construct(Nette\Database\Context $database)  {
        $this->database = $database;
        $this->den = NULL;
        $this->prichod = NULL;
        $this->odchod = NULL;
        $this->prichod_upravene = NULL;
        $this->odchod_upravene = NULL;
        $this->prichod_historia = NULL;
        $this->odchod_historia = NULL;
        $this->poznamka = NULL;
    }
    
    
    
    
}
