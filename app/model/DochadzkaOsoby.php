<?php

namespace App\Model;

use Nette;

/**
 * trieda reprezentuje dochadzku JEDNEJ osoby v casovom intervale OD DO
 */
class DochadzkaOsoby extends Nette\Object {
    /**
     * @var Nette\Database\Context
     */
    private $database;
    
    private $user_id; //id prihlaseneho uzivatela
    private $osoba_id; //id osoby ktorej dochadzku roesime
    private $od; // Nette\Utils\DateTime objekt pociatocneho datumu skumanej dochadzky
    private $do; //Nette\Utils\DateTime objekt koncoveho datumu skumanej dochadzky
    
    //public $dochadzka_den; //pole dni v ktorych sledujeme dochadzku
    //public $dochadzka_prichod; //pole dochadzky v casovom rozmedzi, indexy v poli su id riadkov z databazy dochadzka
    //public $dochadzka_odchod; //pole dochadzky odchodov v casovom rozmedzi, indexy v poli su id riadkov z databazy dochadzka
    
    //public $dochadzka_prichod_upravene; //pole prichodov, ktore boli uz upravovane
    //public $dochadzka_odchod_upravene; //dtto o odchodoch
    
    /*
     * hlavna premenna dochadzky. je to pole OBJEKTOV typu DochadzkajednehoDna
     *  ['id'] - id zaznamu z databazy 
     *  ['prichod'] - DateTime objekt casu a datumu prichodu
     *  ['odchod'] - DateTime objekt odchodu
     *  ['prichod_upravene'] - dateTime objekt prichodu ak sa manualne upravoval prichod. je to nova hodnota UZ NEPOUZIVAM
     *  ['odchod_upravene'] - dateTime objekt odchodu ak sa manualne upravoval prichod. je to nova hodnota UZ NEPOUZIVAM
     *  ['prichod_historia'] = retazec zobrazujuci historiu zmien z tabulky 'dochadzka_log_zmeny'
     *  ['odchod_historia'] = retazec zobrazujuci historiu zmien z tabulky 'dochadzka_log_zmeny'
     */
    public $dochadzka; //pole dochadzky
    
    function __construct(Nette\Database\Context $database, $user_id, $osoba_id)  {
        $this->database = $database;
        $this->user_id = $user_id;
        $this->osoba_id = $osoba_id;
        $this->dochadzka = array(); 
    }
    
    private function createArrivalDatetime( $date ){
     $tempDate = new Nette\Utils\DateTime ( $date. " 0:00:00" ); //vytvori datum s casom 0:00:00 co potrebujeme
     return $tempDate;
    }
    
    private function createLeaveDatetime ( $date ){
        $tempDate = new Nette\Utils\DateTime ( $date." 23:59:59" ); //vytvori datum a s casom 23:59:59 co potrebujeme
        return $tempDate;
    }
    
    //vstup: $od -> retazec vo fortmate dd.mm.yyyy
    //       $do -> retazec vo fortmate dd.mm.yyyy
    public function nacitaj ( $od, $do ){
        $this->od = $this->createArrivalDatetime( $od );
        $this->do = $this->createLeaveDatetime( $do );
                
        //prelojujeme zvoleny rozmedzie
        $interval = new \DateInterval('P1D'); //po akych skokoch budemem iterovat
        $daterange = new \DatePeriod($this->od, $interval ,$this->do);
        foreach($daterange as $index => $date){
            //pre kazdy den si zistime ci ma definovanu dochadzku
            $den = $this->database->table('dochadzka')
                    ->where('den = ?', $date)
                    ->where('people_id = ?', $this->osoba_id)
                    ->fetch();
            $this->dochadzka[$index] = new \App\Model\DochadzkaJednehoDna($this->database); //VYTVORIME SI DOCHADZKU JEDNEHO DNA, je to objekt dochadzky jedneho dna
            $this->dochadzka[$index]->den = $date;
            if ( $den ){ //ked mame taky zaznam
                //na tento den zi zistime historiu prichodov a odchodov z tabulky dochadzka_log_zmeny
                $prichod = "";
                $odchod = "";
                $prichod_historia = $this->database->table('dochadzka_log_zmeny')
                    ->where('dochadzka_id = ?', $den["id"])
                    ->where('typ = ?', 'prichod')
                    ->fetchAll();
                $odchod_historia = $this->database->table('dochadzka_log_zmeny')
                    ->where('dochadzka_id = ?', $den["id"])
                    ->where('typ = ?', 'odchod')
                    ->fetchAll();
                
                if(!empty($prichod_historia)) {                    
                    foreach ($prichod_historia as $i => $cas) {
                        $pom = new Nette\Utils\DateTime( $cas["stary"] ); 
                        $prichod .= $pom->format("G:i").", ";
                    }
                }
                if(!empty($odchod_historia)) {
                    foreach ($odchod_historia as $i => $cas) {
                        $pom = new Nette\Utils\DateTime( $cas["stary"] ); 
                        $odchod .= $pom->format("G:i").", ";
                    }
                }
                
                //vlozi do prichodu bud prichod z databazy, alebo NUL ak je v datab tabulke hodnota NULL, musi to ist takto, lebo DateTime konstruktor ina generuje buduci cas
                $this->dochadzka[$index]->id = $den["id"]; //na prenos id zaznamu
                $this->dochadzka[$index]->prichod = ($den["prichod_timestamp"] != NULL) ? new Nette\Utils\DateTime( $den["prichod_timestamp"] ) : NULL;
                $this->dochadzka[$index]->odchod  = ($den["odchod_timestamp"]  != NULL) ? new Nette\Utils\DateTime( $den["odchod_timestamp"] ) : NULL;
                $this->dochadzka[$index]->prichod_upravene = ($den["upr_prichod_timestamp"] != NULL) ? new Nette\Utils\DateTime( $den["upr_prichod_timestamp"] ) : NULL;
                $this->dochadzka[$index]->odchod_upravene  = ($den["upr_odchod_timestamp"]  != NULL) ? new Nette\Utils\DateTime( $den["upr_odchod_timestamp"] ) : NULL;
                $this->dochadzka[$index]->prichod_historia = $prichod;
                $this->dochadzka[$index]->odchod_historia = $odchod;
            } else { //nenasli sme taky riadok v databaze
                $this->dochadzka[$index]->id = NULL; //na prenos id zaznamu
                $this->dochadzka[$index]->prichod = NULL;
                $this->dochadzka[$index]->odchod = NULL;
                $this->dochadzka[$index]->prichod_upravene = NULL;
                $this->dochadzka[$index]->odchod_upravene = NULL;
                $this->dochadzka[$index]->prichod_historia = NULL;
                $this->dochadzka[$index]->odchod_historia = NULL;
            }
            //pre tento den si natiahneme udaje z tabulky poznamky
            $poznamka = $this->database->table('poznamky')
                    ->where ('den=?', $date)
                    ->fetch();
            if ($poznamka){ //uz mame poznamku na tento den
                $this->dochadzka[$index]->poznamka = $poznamka['poznamka'];
            } else { //este nemame poznamku na tent den
                $this->dochadzka[$index]->poznamka  = NULL;
            }
            
        }// end foreach
    }//end function nacitaj dochadzku
    
    //funckia zmeni hodnotu dochazdky
    //$zaznam_id je id riadku v databazovej tabulke dochadzka
    //$typ je priznak, ci ide o prichod, alebo o odchod, alebo o poznamku
    //$hodnota je hodota timestampu prichdu, alebo odchoduh
    public function storeDochadzka($zaznam_id, $typ, $hodnota){
        $vysl = $this->database->table('dochadzka')->get($zaznam_id); //nacitame si meneny zaznam
        //$hodnota je v tvare hh:mm, musime k nej pridat aj datum, ten si ziskame pomocou id riadku
        $nova_hodnota = new Nette\Utils\DateTime( $vysl["den"] );
        $nova_hodnota->modify($hodnota); //ku dnu pripocitame hodiny
        switch ($typ){
            case "prichod":        
                $stara_hodnota = $vysl["prichod_timestamp"]; //nacitame si staru hodnotu, moye bzt ja NULL
                $pole = array ( 'prichod_timestamp' => $nova_hodnota, 
                                'upr_prichod_timestamp' => $nova_hodnota); //pripravime si meniaci sql dotaz
                $pole_insert_log_zmeny = array( 'typ' => 'prichod',
                                      'stary' => $stara_hodnota,
                                      'novy' => $nova_hodnota,
                                      'users_id' => $this->user_id,
                                      'people_id' => $this->osoba_id,
                                      'dochadzka_id' => $zaznam_id);
                break;
            case "odchod":
                $stara_hodnota = $vysl["odchod_timestamp"];
                $pole = array ( 'odchod_timestamp' => $nova_hodnota, 
                                'upr_odchod_timestamp' => $nova_hodnota);
                $pole_insert_log_zmeny = array( 'typ' => 'odchod',
                                      'stary' => $stara_hodnota,
                                      'novy' => $nova_hodnota,
                                      'users_id' => $this->user_id,
                                      'people_id' => $this->osoba_id,
                                      'dochadzka_id' => $zaznam_id);
                break;
        }//end switch
        try {
            $row= $this->database->table('dochadzka')
                            ->where('id = ?', $zaznam_id)
                            ->update($pole);
            //este si zalogujeme zmeny
            $row2= $this->database->table('dochadzka_log_zmeny')
                        ->insert($pole_insert_log_zmeny);
        } catch ( Nette\Database\ConnectionException $e  ){
            throw new \ErrorException;
        }
    }//end funkcia storeDochadzka

    /*
     * funckia ktora upravy poznamku v databaze dochazky pre dany den a danemo uzivatela
     */
    public function upravPoznamka($den, $poznamka){
        //zistime si ci mame tento den uz definovanu dochadzku
        try {
            $doch = $this->database->table('poznamky')
                    ->where('den = ?', $den)
                    ->where('people_id = ?', $this->osoba_id)
                    ->fetch();
            if ($doch){ //ano, mame uz taku dochadzku, budeme menit
                 $row= $this->database->table('poznamky')
                            ->where('den = ?', $den)
                            ->update( array ('poznamka' => $poznamka) );
            
            } else { //est nemame taku dochadzku, tak vlastne zakladame novu s NULL hodnotami
                $row= $this->database->table('poznamky')
                        ->insert( 
                                array(
                                    'den' => $den,
                                    'people_id' => $this->osoba_id,
                                    'poznamka' => $poznamka  ) 
                            );//end insert
            }
        } catch (\Exception $e){
            throw $e;
        }
    }//end function upravPoznamka
    
    /*
     * funkcia ktora upravi/vlozi do databazy hodnotu pipnutia cloveka v dany den
     */
    public function pipnutieDna (Nette\Utils\DateTime $date, string $typ, string $hodnota) {
        //zistime si ci mame tento den uz definovanu dochadzku
        $doch = $this->database->table('dochadzka')
                    ->where('den = ?', $date)
                    ->where('people_id = ?', $this->osoba_id)
                    ->fetch();
        if ($doch){ //ano, mame uz taku dochadzku, budeme menit
            $this->storeDochadzka($doch["id"], $typ, $hodnota); //vybavene
        } else { //este nie, nemame taku dochadzku, vytvarame novy zaznam
            $this->vytvorNovyZaznam($date, $typ, $hodnota);
        }
    }//end function pipnutieDna
    
    
    /*
     * funkcia na vytvorenie noveho zaznamu v databaze dchadzky,
     * bud vytvori novy prichod, abo novy odchod
     */
    public function vytvorNovyZaznam(Nette\Utils\DateTime $date, string $typ, string $hodnota){
        $nova_hodnota = new Nette\Utils\DateTime( $date ); //vytvorime si datime objekt
        $nova_hodnota->modify($hodnota); //ku dnu pripocitame hodiny
        switch($typ){
            case "prichod":
                $pole_insert = array( 'users_id' => $this->user_id,
                                      'people_id' => $this->osoba_id,
                                      'den' => $date,
                                      'upr_prichod_timestamp' =>  $nova_hodnota,
                                      'prichod_timestamp' =>  $nova_hodnota);
                $pole_insert_log = array( 'typ' => 'prichod',
                                      'novy' => $nova_hodnota,
                                      'users_id' => $this->user_id,
                                      'people_id' => $this->osoba_id);
                break;
            case "odchod":
                $pole_insert = array( 'users_id' => $this->user_id,
                                      'people_id' => $this->osoba_id,
                                      'den' => $date,
                                      'upr_odchod_timestamp' => $nova_hodnota,
                                      'odchod_timestamp' => $nova_hodnota );
                $pole_insert_log = array( 'typ' => 'odchod',
                                      'novy' => $nova_hodnota,
                                      'users_id' => $this->user_id,
                                      'people_id' => $this->osoba_id);
                break;
        } //end switch
        try {
            $row2= $this->database->table('dochadzka')
                            ->insert($pole_insert);
            $row2= $this->database->table('dochadzka_log_zmeny')
                            ->insert($pole_insert_log);
        } catch ( Nette\Database\ConnectionException $e  ){
            throw new \ErrorException;
        }
    }//end function vytvorNovyZaznam
}