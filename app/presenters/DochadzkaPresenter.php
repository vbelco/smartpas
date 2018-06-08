<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Model\Dochadzka; //pripojime triedu 
use App\Model\Osoba; //pripojime triedu 


class DochadzkaPresenter extends BasePresenter
{
    
    /** @var Dochadzka */
    private $dochadzka; //trieda dochadzky
    
    public function __construct(Nette\Database\Context $database, Dochadzka $dochadzka) {
        parent::__construct($database);
        $this->dochadzka = $dochadzka; 
    }
    
    public function renderDefault() //defaultny vypis nasich rfidiek
    {
        $this->dochadzka->setUserId( $this->getUser()->id );
        try {
            $message = $this->dochadzka->initialize();//natiahnutie najnovsich udajov z Log tabulky 
            $this->flashMessage($message);
        } catch (Nette\Application\ApplicationException $e){
            
            $this->flashMessage( $e->getMessage() );
        }
      
        $this->template->posts = $this->dochadzka->ludiaVPraci();   
    }
    
    protected function createComponentPrehladForm() {
        $rows = $this->database->table('people')//nacitali sme si vsetkych nasich aktivnych ludi
                ->where ("users_id = ?", $this->getUser()->id)
                ->where ('active = 1')
                ; 
        foreach ($rows as $row){
            $pole[ $row->id ] = $row->meno;
        }
        $form = new Form;
        
        $form->addText('datum_od', $this->translator->translate('ui.form.from') )
                ->setAttribute('readonly')
                ->setAttribute('class', 'form-control')
                ->setRequired( $this->translator->translate('ui.message.enter_begin_date') );
        
        $form->addText('datum_do',  $this->translator->translate('ui.form.to') )
                ->setAttribute('readonly')
                ->setAttribute('class', 'form-control')
                ->setRequired( $this->translator->translate('ui.message.enter_end_date') );
        
        $form->addCheckboxList('osoby', $this->translator->translate('ui.form.choose_person') ,$pole)
                ->setRequired( $this->translator->translate('ui.message.choose_least_1_person') );
        
        $form->addSubmit('send', $this->translator->translate('ui.form.show') );
        $form->onSuccess[] = [$this, 'prehladFormSubmitted']; //spracovanie formulara bude mat na starosti funckia tejto triedy s nazvom: pridajRFIDFormSubmitted
        
        return $form;
    }
    
    public function prehladFormSubmitted( $form, $values ) {
        //uvodne nastavovacky
        $this->dochadzka->setUserId( $this->getUser()->id );
        $this->dochadzka->setZaokruhlovanieFromDatabase();
        $od = $values['datum_od'];
        $do = $values['datum_do'];
        $osoby = $values['osoby'];
   
        $this->template->osoby = $osoby;//hodime naspet zoznam id osob z formulara
        
        $pole_dochadzka_zaokruhlena = array();
        $pole_meno_osoby = array();
        $pole_celkovy_cas_dochadzky = array();
        //ziskanie  dochadzky
        //prebehneme ludi z formulara a vypise len ludi, sablona potom poziada o dchadzku tychto ludi
        foreach ( $osoby as $clovek ){
            //nacitanie mena osoby
            $o = new Osoba($this->database); //definovane objektu osoby
            $o->InitializeFromDatabase($clovek); //inicializacia z databazy 
            $pole_meno_osoby[$clovek] = $o->getMeno();
 
            $this->dochadzka->generuj_zaokruhlenu_dochadzku_cloveka($clovek, $od, $do);//naplni objekt udajmi o dochaddzke cloveka
            $pole_dochadzka_zaokruhlena[$clovek]= $this->dochadzka->getPoleDochadzky(); //zaokruhli dochadzku podla nastaveneho zaokruhlovania
            $this->dochadzka->sumarizuj(); //spocitame celkovy cas za obdobie
            $temp_celkovy_cas = $this->dochadzka->getCelkovyCasDochadzky(); //ziskame hodnotu v DateInterval objekte
            $temp_hodiny = ($temp_celkovy_cas->days*24) + ($temp_celkovy_cas->h); //pocet hodin
            $temp_minuty = $temp_celkovy_cas->i; //pocet minut
            $pole_celkovy_cas_dochadzky[$clovek]["hodiny"] = $temp_hodiny;
            $pole_celkovy_cas_dochadzky[$clovek]["minuty"] = $temp_minuty;
        }
        $this->template->meno_osoby = $pole_meno_osoby;
        $this->template->posts2 = $pole_dochadzka_zaokruhlena;
        $this->template->celkovy_cas_dochadzky = $pole_celkovy_cas_dochadzky;
    }
    
    protected function createComponentNastavenieForm() {
        //uvodne nastavovacky
        $this->dochadzka->setUserId( $this->getUser()->id );
        $this->dochadzka->setZaokruhlovanieFromDatabase();
        
        $form = new Form;
        
        $items = array(
            '0' => $this->translator->translate('ui.form.items_array_none'),
            '1' => $this->translator->translate('ui.form.items_array_minutes'),
            '15' => $this->translator->translate('ui.form.items_array_15minutes'),
            '30' => $this->translator->translate('ui.form.items_array_30minutes'),
            '60' => $this->translator->translate('ui.form.items_array_1hour')
        );
        
        $form->addRadioList('zaokruhlenie', $this->translator->translate('ui.form.zaokruhlovanie') , $items)
            ->setDefaultValue($this->dochadzka->getZaokruhlovanie());
        
        $form->addSubmit('send', 'Nastav');
        $form->onSuccess[] = [$this, 'nastavenieFormSubmitted']; //spracovanie formulara bude mat na starosti funckia tejto triedy s nazvom: pridajRFIDFormSubmitted
        
        return $form;
    }
    
    public function nastavenieFormSubmitted( $form, $values ) {
        //uvodne nastavovacky
        $this->dochadzka->setUserId( $this->getUser()->id );
        
        $this->dochadzka->updateNastavenie ($values);
    }
    
}