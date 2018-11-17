<?php

/*
 * Trieda repreyentujuca Prcovnu dobu uzivatela a vsetkych jeho zamestnancov
 */

namespace App\Model;

use Nette;
use Nette\Application as NS;
use Nette\Utils\DateTime;

/**
 *
 * @author vbelco-pc
 */
class PracovnaDoba extends Nette\Object
{
    /**
     * @var Nette\Database\Context
     */
    private $database;
    
    private $user_id; //id aktualsne prihlaseneho uzivatela
    
    /*
     * na kolko smien sa pracuje. 
     * Hodnoty 0= 1smenna prevadzka, 1= 2smenna prevadzka, 2= 3smenna prevadzka
     */
    private $pocet_smien; 
    /*pole nastavenia pracovnej doby, ma tvar:  ->index 0,1,2 znamena o ktoru smenu sa jedna
    *        $pole_prac_doby[0]['prichod']  -> php DateInterval object
    *                          ['odchod']   -> php DateInterval object
    */
    private $pole_prac_doby; 
    
    
    
    function __construct(Nette\Database\Context $database, int $user_id = 0)
    {
        $this->database = $database;
        $this->user_id = $user_id;
    }
    
    public function getPocetSmien() { return $this->pocet_smien; }
    public function getPolePracovnejDoby() { return $this->pole_prac_doby; }
    public function getPrichod1() { return $this->pole_prac_doby[0]['prichod']; }
    public function getOdchod1() { return $this->pole_prac_doby[0]['odchod']; }
    public function getPrichod2() { return $this->pole_prac_doby[1]['prichod']; }
    public function getOdchod2() { return $this->pole_prac_doby[1]['odchod']; }
    public function getPrichod3() { return $this->pole_prac_doby[2]['prichod']; }
    public function getOdchod3() { return $this->pole_prac_doby[2]['odchod']; }

    public function setUserId($user_id) { $this->user_id = $user_id; }
    public function setPocetSmienFromDatabase () {
        //zisti nastavenie zaokruhlovania
        $row= $this->database->table('dochadzka_nastavenie')
                ->where('user_id = ?', $this->user_id)
                ->where('vlastnost = ?', 'pocet_smien');
        
        if ($row->count() != 0)  { 
            $temp = $row->fetch();
            $this->pocet_smien = $temp->hodnota;
        }
        else {//este nemame taky zaznam v databaze, tak ho tam rovno doplnime a aj nastavime defaultnu hodnotu
            $pole = array ( 
                'hodnota' => '0',
                'user_id' => $this->user_id,
                'vlastnost' => 'pocet_smien'
                );
            $this->database->table('dochadzka_nastavenie')->insert($pole);
            $this->pocet_smien = '0';
        }
    }
    
    /*pole nastavenia pracovnej doby, ma tvar:  ->index 0,1,2 znamena o ktoru smenu sa jedna
    *        $pole_prac_doby[0]['prichod']  -> php DateInterval object
    *                          ['odchod']   -> php DateInterval object
    */
    public function loadPolePracovnejDoby () {
        $row = $this->database->table('dochadzka_smeny')
                ->where ('user_id = ?', $this->user_id)
                ->fetch();
        if ($row) {//ano, mame uz nastavenu pracovnu dobu
            $this->pole_prac_doby[0]['prichod'] = $row['prichod1'];
            $this->pole_prac_doby[0]['odchod'] = $row['odchod1'];  
            $this->pole_prac_doby[1]['prichod'] = $row['prichod2'];
            $this->pole_prac_doby[1]['odchod'] = $row['odchod2'];  
            $this->pole_prac_doby[2]['prichod'] = $row['prichod3'];
            $this->pole_prac_doby[2]['odchod'] = $row['odchod3'];  
        } else { //este nemame nastavenu pracovnu dobu
            $this->pole_prac_doby = NULL;
        }
    }//end function loadPolePrcovnejDoby
    
    /*
     * zmena nastavenia rozvrhnutia pracovnej doby
     */
    public function updatePracovnaDoba ($pocet_smien, $pole_pracovnej_doby ){
        //nastavenie poctu smien
        $pole = array ( 'hodnota' => $pocet_smien );
        $row= $this->database->table('dochadzka_nastavenie')
                    ->where('user_id = ?', $this->user_id)
                    ->where('vlastnost = ?', 'pocet_smien');    
        $navrt = $row->update($pole);
        if ($navrt  > 1) { throw new \ErrorException; }
        
        //nastavenie pola pracovnej doby
        $row2 = $this->database->table('dochadzka_smeny')
                ->where('user_id = ?', $this->user_id);
        if ( $row2->count() != 0 ){ //uz mame zaznam, idme ho updatnut
            
            try{
                $navrt2 = $row2->update($pole_pracovnej_doby);
                if ( $navrt2 > 1 ) { throw new \ErrorException; }
            } catch (Exception $ex) {
                throw $ex;
            }
        }
        else { //este nemame zaznam
            $pole_pracovnej_doby['user_id'] = $this->user_id; //pridame info o uzivatelovi
            try {
                $row2->insert($pole_pracovnej_doby); //insertnutie noveho zaznamu
            } catch (Exception $ex) {
                throw $ex;
            }
        }//end else
    }//end function updatePracovnaDoba
}
