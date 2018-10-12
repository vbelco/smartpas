<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\Application\UI\Multiplier;
use Nette\Utils\DateTime;

use App\Model\RFID; //pripojime triedu RFID
use App\Model\Citacka; //pripojime triedu Citacky
use App\Model\Uzivatel;

class RFIDPresenter extends BasePresenter
{
    /** @var RFID */
    private $rfid; //trieda rfid
    
    /** @var Citacka */
    private $citacka; //trieda rfid
    
    //pomocne premenne
    private $timestamp = 0; //premenna na uchovanie casu spustenia formulara na nacitavanie novych rfidiek
    private $ktore; //premenna na prepinac priznaku na filtrovanie vypisu rfidiek
    private $posts; //pole riadkov vzpisu, bude sa menit podla filtra
    
    public function __construct(Nette\Database\Context $database, Uzivatel $uzivatel, RFID $rfid) {
        parent::__construct($database, $uzivatel);
        $this->rfid = $rfid;
        $this->ktore = "all"; //na zaciatku budemem zobrazovat vsetky rfidky
    }
    
    public function renderDefault() //defaultny vypis nasich rfidiek
    {
        parent::renderDefault(); //zavolame si nadriadeneho na globalne veci
        if ($this->ktore == "all"){  
            $this->posts = $this->rfid->getAllRfidByUser( $this->getUser()->id ); //nacitanie rfidiek aktivneho uzivatela
        }
        $this->template->ktore = $this->ktore; //uvodny prepinac
        $this->template->posts = $this->posts; //uvodne nacitanie riadkov
    }
    
    public function actionEdituj($rfid_id){
        $this->rfid->InitializeFromDatabase( $rfid_id ); //nacitame informacie z databzy
        $this->template->rfid_id = $rfid_id; //prenos id rfidky, ktora sa edituje
    }
   
    public function actionPriradOsobu($rfid_id){
        $this->rfid->InitializeFromDatabase( $rfid_id ); //nacitame informacie z databzy
        $this->template->cislo_rfid = $this->rfid->getNumber(); //prenesieme do sablony cislo aktualnej rfidky
    }
    
    public function actionDeaktivuj($rfid_id) {
        $this->rfid->InitializeFromDatabase( $rfid_id ); //nacitame informacie z databzy
        $this->template->cislo_rfid = $this->rfid->getNumber(); //prenesieme do sablony cislo aktualnej rfidky
        $this->template->rfid_id = $rfid_id; //prenos id rfidky do sablony
        //kontrola na priradene osoby k tejto RFIDky
        $this->template->osoba = $this->rfid->priradenaOsoba();
        
        //pokial bolo potvrdene, tak ho deaktivujeme, teda nastavime priznak active=0
        if ( $this->getParameter('proceed') ){
            if ( $this->rfid->deaktivuj() ){
                $this->flashMessage($this->translator->translate('ui.message.change_success'),'alert alert-success');
                $this->redirect('RFID:default');
            }
            else {
                $this->flashMessage($this->translator->translate('ui.message.change_fail'), 'alert alert-warning');
                $this->redirect('RFID:default');
            }
        }//end if proceed = 1
    }
    
    public function handleObnov($cislo_citacky, $timestamp){ //signal
        $this->template->obnovene = $cislo_citacky;
        $this->template->timestamp = $timestamp;
        $temp_date = new Nette\Utils\DateTime(); 
        $temp_date->setTimestamp($timestamp);
        $datab_timestamp = $temp_date->format('Y-m-d H:i:s'); //databazovy format
        if ($cislo_citacky){
            //nacitame z logu pipnutia na novych RFIDkach
            $query = 'SELECT log.id, log.rfid_number, log.timestamp
                      FROM log, rfid
                      WHERE log.citacka_id = ?
                        AND log.spracovane = 0
                        AND log.rfid_number != rfid.id
                        AND log.timestamp > ?
                      GROUP BY log.rfid_number';
            $params1 = [$cislo_citacky];
            $params2 = [$datab_timestamp];
            $this->template->nove = $this->database->query($query, $params1, $params2);
        }
    }
    
    public function handlePridajNovu($cislo_rfid, $cislo_citacky, $timestamp, $id_log){
        $this->template->obnovene = $cislo_citacky;
        $this->template->timestamp = $timestamp;
        //tuna zaradime rfidku do systemu
        $active_user_id = $this->getUser()->id;
        $values = array(
          'users_id' =>  $active_user_id,
          'number'   =>  $cislo_rfid,
          'active'   => 1
        );
        try {
            $this->rfid->InsertRfidToDatabase( $values ); //vlozenie novej RFidky
        } catch (Nette\Neon\Exception $e ){
            $this->flashMessage( $e->getMessage() );
        }
        //este uprava tabulky log a nastavenia spracovania na 1
        $row = $this->database->table('log')->get($id_log); //natiahneme si vkladany zaznam
        $pole = array ('spracovane' => '1');
        $row->update($pole);
        
        //tuna znova nacitame este nenacitane rfidky
        if ($cislo_citacky){
            //nacitame z logu pipnutia na novych RFIDkach
            $query = 'SELECT log.id, log.rfid_number, log.timestamp
                      FROM log, rfid
                      WHERE log.citacka_id = ?
                        AND log.spracovane = 0
                        AND log.rfid_number != rfid.id
                        AND log.timestamp > ?
                      GROUP BY log.rfid_number';
            $params1 = [$cislo_citacky];
            $params2 = [$timestamp];
            $this->template->nove = $this->database->query($query, $params1, $params2);
        }
    }
    
    protected function createComponentPriradOsobu() {
        $rows = $this->database->table('people')//nacitali sme si vsetkych nasich aktivnych ludi
                ->where ("users_id = ?", $this->getUser()->id)
                ->where ('active = 1')
                ; 
        $pole = array( 0 => $this->translator->translate('ui.not_assigned') ); //na uvod aj moznost nepriradenia ziadnej osoby
        foreach ($rows as $row){
            $pole[ $row->id ] = $row->meno;
        }
        
        $form = new Form;
        $form->addRadioList('osoby','',$pole);
        $form ->addSubmit('send', $this->translator->translate('ui.form.save') );
        $this->rfid->InitializeFromDatabase( $this->getParameter('rfid_id') ); //nacitame objekt rfid z databazy
        $default = $this->rfid->getOsoba()->getId() != NULL ? $this->rfid->getOsoba()->getId() : 0 ; //do default da bud hodnotu id cloveka, alebo ak je NULL, tak da 0, aby to radiolist zobral 
        $form->setDefaults([
            'osoby' =>  $default//nastavime preddefinovanu hodnotu do radiolistu
        ]);
        $form->onSuccess[] = [$this, 'PriradOsobuFormSubmitted']; //spracovanie formulara bude mat na starosti funckia tejto triedy s nazvom: pridajRFIDFormSubmitted
        return $form;
        
    }
    
    protected function createComponentRFIDForm() {
        $form = new Form;
        $form->addText('number', $this->translator->translate('ui.id_rfid'))
                ->addRule(Form::FILLED, $this->translator->translate('ui.fill_rfid_id'))
                ->addCondition(Form::FILLED);
        
        $postId = $this->getParameter('rfid_id'); //skusime ziskat hodnotu id rfidky, ze ci ju nahodou nejden editovat,
        if ($postId){ //ideme editovat, tak hodime prednastavene hodnoty
            $form->setDefaults([//nastavenie hodnot
                'number' => $this->rfid->getNumber() 
                ]);
            $form->addText('osoba','Priradena osobe')->setDisabled()->setValue( $this->rfid->getOsoba()->getMeno() );
        }
        
        $form->addSubmit('send', $this->translator->translate('ui.form.save')  );
        $form->onSuccess[] = [$this, 'RFIDFormSubmitted']; //spracovanie formulara bude mat na starosti funckia tejto triedy s nazvom: pridajRFIDFormSubmitted
        
        return $form;
    }
    
    protected function createComponentZvolCitackuForm(){
        $form = new Form;
        $this->citacka = new Citacka($this->database, $this->translator); //vytvorime si objekt citacky
        $selection_citacka = $this->citacka->getAllCitackaByUser( $this->getUser()->id ); //nacitame si citacky do pola
        $pole_citacka = array();
        foreach ($selection_citacka as $cit){ //nahadzeme si citacku zo zelection do normalneho pola aby sme si ich mohli dat do formulara
            $pole_citacka[$cit->id] = $cit->id.": ".$cit->name ; //naplennie selctboxu na vyber nasej citacky
        }
        
        $form->addSelect('citacka', $this->translator->translate('ui.reader') , $pole_citacka)
            ->setPrompt($this->translator->translate('ui.form.choose_reader') )
            ->setRequired( $this->translator->translate('ui.message.choose_one_reader') );
        //nahodime si predvolenu hodnotu pokial sme uz v prekliku
        $cislo_citacky = $this->getParameter('cislo_citacky'); //skusime ziskat hodnotu id rfidky, ze ci ju nahodou nejden editovat,
        if ($cislo_citacky){
           $form['citacka']->setDefaultValue($cislo_citacky); 
        }
        
        $form->addSubmit('send', $this->translator->translate('ui.form.continue') );
        
        $form->onSuccess[] = [$this, 'ZvolCitackuFormSubmitted']; //spracovanie formulara bude mat na starosti funckia tejto triedy s nazvom: pridajRFIDFormSubmitted
        return $form;
    }
    
    public function ZvolCitackuFormSubmitted ( $form, $values){
        //funcia pre nacitanie novych RFIDIEK do systemu
        $this->template->zvol_citacku_form_submitted = 1; //priznak stlacenia formulara
        $this->template->cislo_citacky = $values->citacka; //prenos zvoleneho cisla citacky
        $tempDate = new Nette\Utils\DateTime(); 
        $this->template->timestamp = $tempDate->getTimestamp(); //ulozime si cas zvolenia citacky aby sme naitavali nove rfidky len po tomto case
    }
    
    public function RFIDFormSubmitted( $form, $values) { //spusti metodu na spracovanie registracneho formulara
        $active_user_id = $this->getUser()->id;
        $postId = $this->getParameter('rfid_id'); //skusime ziskat hodnotu id rfidky, ze ci ju nahodou nejden editovat,

        if ($postId) { //uz mame, upravujeme
            $this->rfid->updateRfidToDatabaseFromFormular($values, $postId);
            $this->flashMessage(  $this->translator->translate('message.change_success') , 'alert alert-success');
            $this->redirect('RFID:default');
        }
        else { //este nemame, pridavame novu
            if ( $this->rfid->InsertRfidToDatabaseFromFormular($values, $active_user_id ) )
            {
                $this->flashMessage($this->translator->translate('message.change_success'), 'alert alert-success');
                $this->redirect('RFID:default');
            }  
        }      
    }//end function RFIDFormSubmitted
    
    public function PriradOsobuFormSubmitted ( $form, $values) {
        if ( $this->rfid->priradOsobu($values->osoby) ) { //volame metodu triedy na priradenie osoby k rfidke
            $this->flashMessage($this->translator->translate('message.change_success'), 'alert alert-success');
            $this->redirect('RFID:default');
        } else {
            $this->flashMessage($this->translator->translate('message.change_fail'), 'alert alert-warning');
            $this->redirect('RFID:default');
        }
        
    }//end function PriradOsobuFormSubmitted
    
    public function handleFilter($co){ //signal na filtrovanie len nepriradenzch rfidiek
        switch ( $co ){
            case "nepr":
                $this->ktore = "nepr" ; //prepinac, ktore rfidky budeme zobrazovat
                $this->posts = $this->rfid->getNotAssignedByUser( $this->getUser()->id );
                break;
            case "all":
                $this->ktore = "all" ; //prepinac, ktore rfidky budeme zobrazovat
                $this->posts = $this->rfid->getAllRfidByUser( $this->getUser()->id );
                break;
            case "prir":
                $this->ktore = "prir" ; //prepinac, ktore rfidky budeme zobrazovat
                $this->posts = $this->rfid->getOnlyAssignedByUser( $this->getUser()->id );
                break;
        }
        
    }//end function handleFilter
    
}