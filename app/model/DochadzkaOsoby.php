<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model;

use Nette;

/**
 */
class DochadzkaOsoby extends Nette\Object {
    /**
     * @var Nette\Database\Context
     */
    private $database;
    
    private $user_id; //id prihlaseneho uzivatela
    private $osoba_id; //id osoby ktorej dochadzku roesime
    private $od; //timestamp od
    private $do; //timestamp do
    
    public $dochadzka_den; //pole dni v ktorych sledujeme dochadzku
    public $dochadzka_prichod; //pole dochadzky v casovom rozmedzi, indexy v poli su id riadkov z databazy dochadzka
    public $dochadzka_odchod; //pole dochadzky odchodov v casovom rozmedzi, indexy v poli su id riadkov z databazy dochadzka
    
    function __construct(Nette\Database\Context $database, $user_id, $osoba_id)  {
        $this->database = $database;
        $this->user_id = $user_id;
        $this->osoba_id = $osoba_id;
    }
    
    private function createArrivalDatetime( $date ){
     $tempDate = new Nette\Utils\DateTime ( $date. " 0:00:00" ); //vytvori datum s casom 0:00:00 co potrebujeme
     return $tempDate;
    }
    
    private function createLeaveDatetime ( $date ){
        $tempDate = new Nette\Utils\DateTime ( $date." 23:59:59" ); //vytvori datum a s casom 23:59:59 co potrebujeme
        return $tempDate;
    }
    
    public function nacitaj ( $od, $do ){
        $this->od = $this->createArrivalDatetime( $od );;
        $this->do = $this->createLeaveDatetime( $do );
        //natiahneme riadky s dochadzko v danom termine
        $dni = $this->database->table('dochadzka')
                    ->where ('users_id = ?', $this->user_id )
                    ->where ('people_id = ?', $this->osoba_id )
                    ->where ('den >= ?', $this->od->format('Y-m-d H:i:s') )
                    ->where ('den <= ?', $this->do->format('Y-m-d H:i:s') )
                    ->fetchall();
        
        foreach ($dni as $index => $den) {
                $this->dochadzka_prichod[$index] = $den["prichod_timestamp"];
                $this->dochadzka_odchod[$index] = $den["odchod_timestamp"];
                $this->dochadzka_den[$index] = $den["den"];
        }
    }//end function nacitaj dochadzku
    
    //funckia zmeni hodnotu dochazdky
    //$zaznam_id je id riadku v databazovej tabulke dochadzka
    //$typ je priznak, ci ide o prichod, alebo o odchod
    //$hodnota je hodota timestampu prichdu, alebo odchoduh
    public function storeDochadzka($zaznam_id, $typ, $hodnota){
        //$hodnota je v tvare hh:mm, musime k nej pridat aj datum, ten si ziskame pomocou id riadku
        $vysl = $this->database->table('dochadzka')->get($zaznam_id);
        $nova_hodnota = new Nette\Utils\DateTime( $vysl["den"] );
        $nova_hodnota->modify($hodnota);
        switch ($typ){
            case "prichod":
                $pole = array ( 'prichod_timestamp' => $nova_hodnota, 
                                'upr_prichod_timestamp' => $nova_hodnota);
                break;
            case "odchod":
                $pole = array ( 'odchod_timestamp' => $nova_hodnota, 
                                'upr_odchod_timestamp' => $nova_hodnota);
                break;
        }//end switch
        $row= $this->database->table('dochadzka')
                            ->where('id = ?', $zaznam_id)
                            ->update($pole);
    }//end funkcia storeDochadzka
}
