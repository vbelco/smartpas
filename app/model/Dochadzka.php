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
            // RFID UZ MUSI BYT PRIRADENA CLOVEKU, INAK ODIGNORUJE
            $rows2 = $this->database->table('rfid')
                    ->select('people_id')
                    ->where('number = ?', $row->rfid_number)
                    ->where('users_id = ?', $this->user_id )
                    ->fetch();
            if ( $rows2 && ( $rows2->people_id != NULL) ){ //mame taku rfidku, co je u nas v databaze
                // vlozime zaznam do databazy
                $message .= $this->vlozZaznamDoDatabazy($row->id);
                //nastavime polozky ako spracovane
                $row->update([ 'spracovane' => '1' ]); //nastavenie priznaku spracovania v logovacom subore 
            } else { //takuto rfid nemame u nas v databaze, alebo nieje priradena niejakemu cloveku
                //jednoducho ju dame ako spracovanu a basta, ide do zabudnutia
                $row->update([ 'spracovane' => '1' ]);
            }//end else 
        }//end foreach
        return $message;
    }// end function initialize()
    
    //funckia zobrazi ludi ktori maju zaznamenany prichod ale nemaju zaznamenany odchod
    public function ludiaVPraci()
    {   
        return $this->database->query( //musim pouzit tento zlozeny prikaz, pretoze podla tutorialu to vyhadzuje chybu
                "SELECT people.meno, MAX(dochadzka.prichod_timestamp) AS prichod_timestamp
                 FROM people, dochadzka
                 WHERE dochadzka.odchod_timestamp IS NULL
                    AND dochadzka.users_id = ".$this->user_id." 
                    AND dochadzka.people_id = people.id 
                    AND people.active = 1
                    AND DATE(dochadzka.prichod_timestamp) = CURDATE()
                 GROUP BY people.meno
                 ORDER BY dochadzka.prichod_timestamp DESC
                " );
    }
    
    //vlozi zaznam do dochadzky, vkladane bude cislo rfid
    public function vlozZaznamDoDatabazy ($id_zaznamu){        
        $vysl = $this->database->table('log')->get($id_zaznamu); //natiahneme si vkladany zaznam
        $this->rfid_number= $vysl->rfid_number; 
        $timestamp = $vysl->timestamp;
        
        $vysl_rfid = $this->database->table('rfid') //zistime id cloveka, ktory si pipol
                ->select('people_id')
                ->where('rfid.number = ?', $this->rfid_number)
                ->fetch();
        if($vysl_rfid) {  // je to nas clovek, ostatnych odignoruje, teda nepriradene ignoruje
            $this->clovek_id = $vysl_rfid->people_id; 
            switch ($this->akyTypZaznamu($timestamp) ){
                case 'prichod':
                    $this->vlozPrichod($timestamp);
                    break;
                case 'odchod':
                    $this->vlozOdchod($timestamp);
                    break;
            }
            //if ( $this->jeZaznamOdchod($timestamp) ) { $this->vlozOdchod($timestamp); }
            //else if ( $this->jeZaznamPrichod($timestamp) ) { $this->vlozPrichod($timestamp); }
        }//end if mame nasho cloveka
    } //end function vloz zanzam do databazy
    
    public function vlozPrichod($timestamp){
        //vlozenie noveho ridku do dochadzky
        $row = $this->database->table('dochadzka')->insert([
                        'people_id' => $this->clovek_id,
                        'prichod_timestamp' => $timestamp,
                        'users_id' => $this->user_id,
                        'den' => $timestamp->format("Y-m-d")
                        ]);
    }//end function vloz prichod
    
    public function vlozOdchod($timestamp){
        //nacitanie posledneho zaznamu
        $row = $this->database->table('dochadzka')
                    ->where('users_id = ?', $this->user_id)
                    ->where('people_id = ?', $this->clovek_id)
                    ->order('id DESC')
                    ->limit('1')
                    ->fetch();
        //   ODCHOD
        if ( $row->odchod_timestamp  ){//nemame taky zaznam, ale ide o odchod, teda musime zalozit novy riadok s prichodom NULL 
            $this->database->table('dochadzka')->insert([
                        'people_id' => $this->clovek_id,
                        'odchod_timestamp' => $timestamp,
                        'users_id' => $this->user_id ,
                        'den' => $timestamp->format("Y-m-d")
                    ]);
        } else { //posledny zaznam mame NULL odchodom_timestamp, teda ho ideme updatnut 
            $this->database->table('dochadzka')
                    ->where('id', $row->id )
                    ->update([
                        'odchod_timestamp' => $timestamp
                    ]);
        }
    }//end function vloz odchod
    
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
    
    //vypise posledne zaznamy v dochadzke 
    // vstup: pocet zaznamov
    //vystup: pole dochadzky
    public function getDochadzka( $pocet_zaznamov ) {
        return $this->database->table('dochadzka')
                ->where('users_id = ?', $this->user_id )
                ->order('id DESC')
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
                    ->where ('den >= ?', $this->datum_od->format('Y-m-d H:i:s') )
                    ->where ('den <= ?', $this->datum_do->format('Y-m-d H:i:s') )
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
                    ->where ('den >= ?', $this->datum_od->format('Y-m-d H:i:s') )
                    ->where ('den <= ?', $this->datum_do->format('Y-m-d H:i:s') );   
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
                    //pokial prichod je 1970-01-01 12:00:00 dame NULL
                    if ($den['prichod']){
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
                    }//end if mame prichod rozdielny od NULL
                    else {
                        $this->pole_dochadzky[$key]['cas_v_praci'] = 0 ; //ked si zabudol rano pipnut
                    } //end else
                    
                    //odchod
                    //len v pripade ze je odchod uz nastaveny, a nieje NULL ALEBO nieje 1970-01-01 12:00:00
                    if ( $den['odchod'] ){
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
            
        } else { return false; }
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
                if ($this->pole_dochadzky[$key]['cas_v_praci']) { //pokial mame definovany cas v praco rozdielny od 0
                    $e->add( $this->pole_dochadzky[$key]['cas_v_praci']);
                }
            }
            $this->celkovy_cas = $f->diff($e);//odratame cas
        }//end if ze mame nejaku dochadzku

    }//end function sumarizuj
       
    /*
     * funkcia zisti o aky typ zaznamu ide, bud o prichod, alebo o odchod
     * vstup: timestamp udalosti
     * vystup:  'prichod'  'odchod'
     * funckia pracuje s tabulkou dochadzka a analyzuje posledny riadok
     */
    public function akyTypZaznamu ($timestamp){
        $navrat = ""; //navratova hodnota
        //nacitame si posledny den v praci
            $row = $this->database->table('dochadzka')
                    ->where('users_id = ?', $this->user_id)
                    ->where('people_id = ?', $this->clovek_id)
                    ->order('id DESC')
                    ->limit(1)
                    ->fetch();
        //posledny riadok moze mat tvar: prichod odchod
        //                               cas      cas
        //                               NULL     cas
        //                                cas     NULL
        //podla posledneho tvaru si nahodime defaultny typ zaznamu
        if ($row->prichod_timestamp){
            if($row->odchod_timestamp){ 
                $navrat = 'prichod'; 
            }
            else { 
                $navrat = 'odchod'; 
            }
        } else { 
            $navrat = 'prichod'; 
        }
        /*
         * oblast kontrol na hranicne situace
         */
        $pracovna_doba = new \App\Model\PracovnaDoba($this->database, $this->user_id );
        $pracovna_doba->setPocetSmienFromDatabase(); //nacitanie nastaveni
        $pracovna_doba->loadPolePracovnejDoby(); //nacitanie pracovnej doby z databazy
        if($row->prichod_timestamp) {
            $rozdiel = $timestamp->getTimestamp() - $row->prichod_timestamp->getTimestamp();
        } else {
            $rozdiel = $timestamp->getTimestamp() - $row->odchod_timestamp->getTimestamp() + (12*60*60);
        }
        $hodiny_pipnutie = $timestamp->format('G'); //vycucneme si hodiny
        $hodiny_odchod = $pracovna_doba->getOdchod1()->format("%h");
        $hodiny_prichod = $pracovna_doba->getPrichod1()->format("%h");
        //kontrola zabudnuteho prichodu
        //situacia: je dalsi den a
        // /preslo viac ako 20 hodin od predosleho prichodu, alebo viac ako 8 hodin od predosleho odchodu/ 
        // a je cas definovaneho odchodu v nastaveniach   
            if ( ( $rozdiel > (20*60*60) ) && ( abs((int)$hodiny_odchod - (int)$hodiny_pipnutie) < 2 ) ){
                $navrat = 'odchod';
            } 
        //kontrola zabudnuteho odchodu
        //situacia: je dalsi den rano 
        ///preslo viac ako 20 hodin od predosleho prichodu, alebo viac ako 8 hodin od predosleho odchodu// 
        //   AND je v case prichodu
            if ( ( $rozdiel > (20*60*60) ) && ( abs((int)$hodiny_prichod - (int)$hodiny_pipnutie) < 2 ) ){
                $navrat = 'prichod';
            }
        
        return $navrat;
    }//end function akyTypZaznamu
}//END CLASS
