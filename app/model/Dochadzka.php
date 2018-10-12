<?php
/*
trieda reprezentujuca RFID klucenku, kartu a pod....
  */

namespace App\Model;

use Nette;
use Nette\Application as NS;
use Nette\Utils\DateTime;

class Dochadzka extends Nette\Object
{
    /**
     * @var Nette\Database\Context
     */
    private $database;
    
    private $user_id; //id aktualsne prihlaseneho uzivatela
    
    //udaje dochadzky
    // na kolko sa bude zaokruhlovat dochadzka
    //        '0' => ' Ziadne',
    //        '1' => ' Minuty',
    //        '15' => ' 15 minut',
    //        '30' => ' 30 minut',
    //        '60' => ' 1 hodina'
    private $zaokruhlovanie; 
    private $tolerancia; // tolerancia neskoreho prichodu, napr 7:02 pri tolerancii 2 minuty este da prichod ako 07:00
    private $vypis_real_casov; //priznak, ci bude vo vypisoch vypisovat aj realne caso popri zaokruhlenym
    
    private $clovek_id;//clovek, ktoreho dochadzku riesime
    private $datum_od; //odkedy pocitame, objedkt DateTime
    private $datum_do; //dokedy pocitame, objekt DateTime
    private $celkovy_cas; //celkovy cas dochadzky za sledovane obdobie vo formate DateInterval
    private $rfid_number; //id rfid ktora si pipla, bude je niekomu priradena, alebo nieje 
    
    /*
     * pole najdenej dochadzky podla datumu od-do
     * pole ma tvar $pole_dochadzky[id zaznamu][prichod] -> Nette\Utils\DateTime 
     *                                         [odchod]  -> Nette\Utils\DateTime 
     *                                         [cas_v_praci] -> objekt DateInterval 
     *                              
     *      */
    private $pole_dochadzky; //dochadzka ludi za dany interval
    private $raw_pole_dochadzky; // nezaokruhlena dochadzka ludi, ma rovnku strukturu ako $pole_dochadzky
    
    /*
     * definovanie pracovnej doby
     */
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
    
    
    
    function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }
    
    private function createArrivalDatetime( $date ){
     $tempDate = new Nette\Utils\DateTime ( $date. " 0:00:00" ); //vytvori datum s casom 0:00:00 co potrebujeme
     return $tempDate;
    }
    
    private function createLeaveDatetime ( $date ){
        $tempDate = new Nette\Utils\DateTime ( $date." 23:59:59" ); //vytvori datum a s casom 23:59:59 co potrebujeme
        return $tempDate;
    }
    
    public function initialize()
    {
        $message = "";
        //prebehne vsetky zaznamy z Logu kt. maju priznak ako nespracovane a prevedie ich do dochadzky
        $rows = $this->database->table('log')
                ->where('citacka.users_id = ?', $this->user_id) //odfiltruje len tie zaznamy, ktore patria nasej citacke
                ->where('citacka.active = 1') //nacita len aktivne citacky
                ->where('spracovane = 0') //zoberieme len nespracovane udaje
                ;
        foreach ($rows as $row){
            //kontrola ci tento zaznam z Logu je pipnuty nejakou nasou priradenou rfid        
            $rows2 = $this->database->table('rfid')
                    ->select ('*')
                    ->where('number = ?', $row->rfid_number)
                    ->where('users_id = ? OR users_id IS NULL', $this->user_id ); //nacita len nami vlozene RFID ktora je niekomu priradena alebo este nieje nikomu priradena 
            if ($rows2){ //mame taku rfidku
                // vlozime zaznam do databazy
                $message .= $this->vlozZaznamDoDatabazy($row->id);
                //nastavime polozky ako spracovane
                $pole = array ('spracovane' => '1');
                $row->update($pole); //nastavenie priznaku spracovania v logovacom subore
            } else { //nemame taku rfidku v nasej databaze, tak ju dame do nepriradenych rfidiek
                
            }//end else 
        }//end foreach
        return $message;
    }// end function initialize()
    
    //funckia zobrazi ludi ktori maju zaznamenany prichod ale nemaju zaznamenany odchod
    public function ludiaVPraci()
    {   
        return $this->database->query( //musim pouzit tento zlozeny prikaz, pretoze podla tutorialu to vyhadzuje chybu
                "SELECT people.meno, dochadzka.prichod_timestamp, dochadzka.rfid_number
                 FROM people, dochadzka
                 WHERE dochadzka.odchod_timestamp IS NULL
                    AND dochadzka.users_id = ".$this->user_id." 
                    AND dochadzka.people_id = people.id 
                    AND people.active = 1
                 ORDER BY dochadzka.prichod_timestamp ASC
                "
                );
    }
    
    //vlozi zaznam do dochadzky, vkladane bude cislo rfid
    public function vlozZaznamDoDatabazy ($id_zaznamu){        
        $vysl = $this->database->table('log')->get($id_zaznamu); //natiahneme si vkladany zaznam
        $this->rfid_number= $vysl->rfid_number;
        
        $vysl_rfid = $this->database->table('rfid') //zistime id cloveka, ktory si pipol
                ->select('people_id')
                ->where('rfid.number = ?', $this->rfid_number)
                ->fetch();
        
        if($vysl_rfid){ //mame cloveka na ktoreho ukazuje
            $this->clovek_id = $vysl_rfid->people_id; //priradenie cloveka k rfidke
            if ($this->clovek_id != NULL ){ 
                //pokial mame otvoreny zaznam, tak ho updatneme 
                //pokial nemame taky zaznam, tak ho zalozime
                $vysl_dochadzka = $this->database->table('dochadzka')
                    ->where('users_id = ?', $this->user_id)
                    ->where('people_id = ?', $this->clovek_id)
                    ->where('odchod_timestamp IS NULL' )
                   ;
                if ( $vysl_dochadzka->count() != 0  ){ //mame tento zaznam, ideme ho len updatnut
                    //kontrola na zabudnute pipnutie, skontrolujeme ci nahodou si clovek vcera nezabudol odpipnut
                    
                    
                    $vysl2 = $vysl_dochadzka->fetch();
                    //vypocitame cas v praci          
                    $pole = array (
                        'odchod_timestamp' => $vysl->timestamp,
                        );
                    $vysl_dochadzka->update($pole);
                }
                else { //este nemame zaznam, tak tam nahodime prichod
                    $pole = array (
                        'people_id' => $this->clovek_id,
                        'prichod_timestamp' => $vysl->timestamp,
                        'users_id' => $this->user_id
                        );
                     $vysl_dochadzka->insert($pole);
                    //echo "Cas".$vysl->timestamp;
                    //echo "Insert <br>";
                }
            }//end if people != NULL
            else { //nepriradena RFID, namiesto cisla cloveka budeme davat odkaz na cislo RFID
                //pokial mame otvoreny zaznam, tak ho updatneme 
                //pokial nemame taky zaznam, tak ho zalozime
                $vysl_dochadzka = $this->database->table('dochadzka')
                    ->where('users_id = ?', $this->user_id)
                    ->where('rfid_number = ?', $this->rfid_number)
                    ->where('odchod_timestamp IS NULL' )
                   ;
                if ( $vysl_dochadzka->count() != 0  ){ //mame tento zaznam, ideme ho len updatnut
                    $vysl2 = $vysl_dochadzka->fetch();
                    //vypocitame cas v praci
                    $pole = array (
                        'odchod_timestamp' => $vysl->timestamp,
                        );
                    $vysl_dochadzka->update($pole);
                }
                else { //este nemame zaznam, tak tam nahodime prichod
                    $pole = array (
                        'rfid_number' => $this->rfid_number,
                        'people_id' => NULL,
                        'prichod_timestamp' => $vysl->timestamp,
                        'users_id' => $this->user_id
                        );
                     $vysl_dochadzka->insert($pole);
                }
            }//ens else nepriradena rfidkz
        }//end if($vysl_rfid)
                
    }
    public function setUserId($user_id){$this->user_id = $user_id; }
    public function setDatumOd($dt) {$this->datum_od = $dt;}
    public function setDatumDo($dt) {$this->datum_do = $dt;}
    public function setPoleLudi($ludia) {$this->pole_ludi = $ludia; }
    public function setZaokruhlovanie($dt) {$this->zaokruhlovanie = $dt;}
    public function setZaokruhlovanieFromDatabase () {
        //zisti nastavenie zaokruhlovania
        $row= $this->database->table('dochadzka_nastavenie')
                ->where('user_id = ?', $this->user_id)
                ->where('vlastnost = ?', 'zaokruhlovanie');
        
        if ($row->count() != 0)  { 
            $temp = $row->fetch();
            $this->zaokruhlovanie = $temp->hodnota;
        }
        else {//este nemame taky zaznam v databaze, tak ho tam rovno doplnime a aj nastavime defaultnu hodnotu
            $pole = array ( 
                'hodnota' => '0',
                'user_id' => $this->user_id,
                'vlastnost' => 'zaokruhlovanie'
                );
            $this->database->table('dochadzka_nastavenie')->insert($pole);
            $this->zaokruhlovanie = '0';
        }
    }
    
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
    
    public function setToleranciaFromDatabase () {
        //zisti nastavenie zaokruhlovania
        $row= $this->database->table('dochadzka_nastavenie')
                ->where('user_id = ?', $this->user_id)
                ->where('vlastnost = ?', 'tolerancia');
        
        if ($row->count() != 0)  { 
            $temp = $row->fetch();
            $this->tolerancia = $temp->hodnota;
        }
        else {//este nemame taky zaznam v databaze, tak ho tam rovno doplnime a aj nastavime defaultnu hodnotu
            $pole = array ( 
                'hodnota' => '0',
                'user_id' => $this->user_id,
                'vlastnost' => 'tolerancia'
                );
            $this->database->table('dochadzka_nastavenie')->insert($pole);
            $this->tolerancia = '0';
        }
    }
    
    public function setVypisRealCasovFromDatabase () {
        //zisti nastavenie zaokruhlovania
        $row= $this->database->table('dochadzka_nastavenie')
                ->where('user_id = ?', $this->user_id)
                ->where('vlastnost = ?', 'vypis_real_casov');
        
        if ($row->count() != 0)  { 
            $temp = $row->fetch();
            $this->vypis_real_casov = $temp->hodnota;
        }
        else {//este nemame taky zaznam v databaze, tak ho tam rovno doplnime a aj nastavime defaultnu hodnotu
            $pole = array ( 
                'hodnota' => '0',
                'user_id' => $this->user_id,
                'vlastnost' => 'vypis_real_casov'
                );
            $this->database->table('dochadzka_nastavenie')->insert($pole);
            $this->vypis_real_casov = '0';
        }
    }
    
    public function getZaokruhlovanie() {return $this->zaokruhlovanie;}
    public function getPoleDochadzky() { return $this->pole_dochadzky; }
    public function getPoleRawDochadzky() { return $this->raw_pole_dochadzky; }
    public function getCelkovyCasDochadzky() { return $this->celkovy_cas; }
    public function getTolerancia () { return $this->tolerancia; }
    public function getVypisRealCasov() { return $this->vypis_real_casov; }
    public function getPocetSmien() { return $this->pocet_smien; }
    public function getPolePracovnejDoby() { return $this->pole_prac_doby; }
    
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
        } else { //este nemame nastavenu pracovnu dobu
            $this->pole_prac_doby = NULL;
        }
    }//end function loadPolePrcovnejDoby
    
    //vypise posledne zaznamy v dochadzke 
    // vstup: pocet zaznamov
    //vystup: pole dochadzky
    public function getDochadzka( $pocet_zaznamov ) {
        return $this->database->table('dochadzka')
                ->where('users_id = ?', $this->user_id )
                ->order('prichod_timestamp DESC')
                ->limit($pocet_zaznamov);
    }
    
    //updatne nastavenia aplikacie dochadzka v databaze
    /*
     * @throws Exception
     */
    public function updateNastavenie ( $values ){
        //naastavenie zaokruhlovania
            $pole1 = array ( 'hodnota' => $values['zaokruhlenie'] );
            $row1= $this->database->table('dochadzka_nastavenie')
                    ->where('user_id = ?', $this->user_id)
                    ->where('vlastnost = ?', 'zaokruhlovanie');
            
            $navrt1 = $row1->update($pole1);
            if ($navrt1  > 1) { throw new \ErrorException; }
            //nastavenie tolerancie neskoreho prichodu
            $pole2 = array ( 'hodnota' => $values['late_arrival'] );
            $row2= $this->database->table('dochadzka_nastavenie')
                    ->where('user_id = ?', $this->user_id)
                    ->where('vlastnost = ?', 'tolerancia');
            $navrt2 = $row2->update($pole2);
            if ($navrt2  > 1) { throw new \ErrorException; }
            //nastavenie realneho vypisovania casov popri zaokruhlenych
            $pole3 = array ( 'hodnota' => $values['real_times'] );
            $row3= $this->database->table('dochadzka_nastavenie')
                    ->where('user_id = ?', $this->user_id)
                    ->where('vlastnost = ?', 'vypis_real_casov');
            $navrt3 = $row3->update($pole3);
            if ($navrt3  > 1) { throw new \ErrorException; }
    }
    
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
    
    public function generuj_zaokruhlenu_dochadzku_cloveka( $clovek_id, $od, $do ){
        //nacitanie udajov do triedy
        $this->clovek_id = $clovek_id;
        //vyrobenie formatu datumu porozumentelneho sql serverom
        $this->datum_od = $this->createArrivalDatetime( $od );
        $this->datum_do = $this->createLeaveDatetime( $do );
        
        //natiahneme riadky s dochadzko v danom termine
        //prebehneme zaskrtnute osoby
        $rows = $this->database->table('dochadzka')
                    ->where('users_id = ?', $this->user_id)
                    ->where ( 'people_id = ?', $this->clovek_id )
                    ->where ('prichod_timestamp >= ?', $this->datum_od->format('Y-m-d H:i:s') )
                    ->where ('prichod_timestamp <= ?', $this->datum_do->format('Y-m-d H:i:s') )
                    ->where ('odchod_timestamp <= ? ', $this->datum_do->format('Y-m-d H:i:s') )
                ->fetchAll();   //no tot musi byt inak to nenaplni data v selection
        foreach ($rows as $row){
            $this->pole_dochadzky[$row->id]['prichod'] = $row->prichod_timestamp;
            $this->pole_dochadzky[$row->id]['odchod'] = $row->odchod_timestamp;
        }
        $this->zaokruhliCasVPraci(); //zaokruhli cas v pracipre tohto cloveka v danom intervale
        //\Tracy\Dumper::dump($this->pole_dochadzky);
    }
    
    public function generuj_raw_dochadzku_cloveka ( $clovek_id, $od, $do ) {
        //nacitanie udajov do triedy
        $this->clovek_id = $clovek_id;
        //vyrobenie formatu datumu porozumentelneho sql serverom
        $this->datum_od = $this->createArrivalDatetime( $od );
        $this->datum_do = $this->createLeaveDatetime( $do );
        
        //natiahneme riadky s dochadzko v danom termine
        //prebehneme zaskrtnute osoby
        $rows_raw_dochadzka = $this->database->table('dochadzka')
                    ->where('users_id = ?', $this->user_id)
                    ->where ( 'people_id = ?', $this->clovek_id )
                    ->where ('prichod_timestamp >= ?', $this->datum_od->format('Y-m-d H:i:s') )
                    ->where ('prichod_timestamp <= ?', $this->datum_do->format('Y-m-d H:i:s') )
                    ->where ('odchod_timestamp <= ? ', $this->datum_do->format('Y-m-d H:i:s') );   
        $navrat_raw_dochadzka = $rows_raw_dochadzka->fetchAll(); //no tot musi byt inak to nenaplni data v selection
        foreach ($rows_raw_dochadzka as $row_raw_dochadzka){
            //naplnime aj hrube casy
            $this->raw_pole_dochadzky[$row_raw_dochadzka->id]['prichod'] = $row_raw_dochadzka->prichod_timestamp;
            $this->raw_pole_dochadzky[$row_raw_dochadzka->id]['odchod'] = $row_raw_dochadzka->odchod_timestamp;
        }
    }
    
    public function zaokruhliCasVPraci(){
        if ( isset($this->pole_dochadzky) ){ //mozeme vratit nejaky cas az ked to mame nahodene
            $this->setZaokruhlovanieFromDatabase();//zistime si zaokruhlovanie
            $this->setToleranciaFromDatabase(); //zistime ci je nastavena tolerancia oneskoreneho prichodu
            //prebehneme si dochadzku a zratame
            foreach ( $this->pole_dochadzky as $key => $den ){
                    //prichod
                    //$temp je hodnota prichodu v timestampe, teda v sekundach
                    $temp = $this->zaokruhlovanie ? $den['prichod']->getTimestamp() / ($this->zaokruhlovanie * 60 ) : $den['prichod']->getTimestamp(); //vylucenie delenia 0 cez ternarny operator   
                    $temp = ceil ($temp); //zaokruhlime nadol aby sme dostali cele jednoty
                    $temp = $this->zaokruhlovanie ? $temp * $this->zaokruhlovanie * 60 : $temp; //vypocitame spet zaokruhleny cas, ale len v pripade, kedy zaokruhlovanie je rozdielne od 0
                    $temp_o_jeden_menej = ( $temp - ( $this->zaokruhlovanie * 60 )) ; //skok o jednu zaokruhlenu polozku menej
                    $rozdiel = $den['prichod']->getTimestamp() - $temp_o_jeden_menej ; //zistime o kolko meska nas pracovnik v sekundach
                    if ( $rozdiel <= ($this->tolerancia*60) ) { //este je v meskani v tolerancii prichodu
                       $den['prichod']->setTimestamp($temp_o_jeden_menej); // dame mu o jeden zaokruhlovaci skok menej 
                    } else {
                        $den['prichod']->setTimestamp($temp) ; //nastavime spet
                    }
                    //odchod
                    //len v pripade ze je odchod uz nastaveny, a nieje NULL
                    if ( $den['odchod'] != NULL ){
                        $temp = $this->zaokruhlovanie ? $den['odchod']->getTimestamp() / ($this->zaokruhlovanie * 60 ) : $den['odchod']->getTimestamp(); //vylucenie delenia 0 cez ternarny operator
                        $temp = floor($temp); //zaokruhlime nadol aby sme dostali cele jednoty
                        $temp = $this->zaokruhlovanie ? $temp * $this->zaokruhlovanie * 60 : $temp; //vypocitame spet zaokruhleny cas, ale len v pripade, kedy zaokruhlovanie je rozdielne od 0
                        $den['odchod']->setTimestamp($temp) ; //nastavime spet
                        //prepocitanie casu v praci
                        //$hodnota_casu = $den['odchod']->getTimestamp() - $den['prichod']->getTimestamp();
                        $hodnota_casu = $den['prichod']->diff( $den['odchod'] );
                        $this->pole_dochadzky[$key]['cas_v_praci'] = $hodnota_casu; //priamy pristup do pola musi byt cez operato $this->
                    }
                    else {
                        $this->pole_dochadzky[$key]['cas_v_praci'] = 0 ; //ked je este v praci, tak dame 0
                    } //end else
            }//end foreach
            
        } else return false;
    }
    
    public function vycisti() {
        if ( is_array($this->pole_dochadzky) ){ 
            array_splice($this->pole_dochadzky, 0); //vycistene pole
        }
        if ( is_array($this->raw_pole_dochadzky) ){ 
            array_splice($this->raw_pole_dochadzky, 0); //vycistene pole
        }
    }
    
    //funckia spocita celkovy cas v praci za sledovane obdobie
    public function sumarizuj(){
        $e = new Nette\Utils\DateTime('00:00');
        $f = clone $e;
        if ( count($this->pole_dochadzky)  ){
            foreach ( $this->pole_dochadzky as $key => $den ){
                $e->add( $this->pole_dochadzky[$key]['cas_v_praci']);
            }
            $this->celkovy_cas = $f->diff($e);//odratame cas
        }//end if ze mame nejaku dochadzku

    }//end function sumarizuj
    
    public function jeZaznamPrichod ($timestamp) {
        //nacitame 5 poslednych prichodov
        $vysl = $this->database->table('dochadzka')
                    ->where('users_id = ?', $this->user_id)
                    ->where ( 'people_id = ?', $this->clovek_id )
                    ->order('prichod_timestamp DESC')
                    ->limit('5');
        //vypocitame si priemerny prichod
//                    ->aggregation('AVG(prichod_timestamp) as prichod');
        $aktual_cas = new Nette\Utils\DateTime($timestamp);
        $priem_prichod = new Nette\Utils\DateTime($vysl['prichod']);
        
        
                
    }

}
