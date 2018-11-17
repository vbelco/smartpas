<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Model\Dochadzka; //pripojime triedu 
use App\Model\Osoba; //pripojime triedu 
use App\Model\Uzivatel;

class DochadzkaPresenter extends BasePresenter
{
    
    /** @var Dochadzka */
    private $dochadzka; //trieda dochadzky
    
    public function __construct(Nette\Database\Context $database, Uzivatel $uzivatel, Dochadzka $dochadzka) {
        parent::__construct($database, $uzivatel);
        $this->dochadzka = $dochadzka; 
    }
    
    public function renderDefault() 
    {        
        parent::renderDefault(); //zavolame si nadriadeneho na globalne veci
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
        $this->dochadzka->setToleranciaFromDatabase(); //nastavenie predvolenej tolerancie neskorzch prichodov
        $this->dochadzka->setVypisRealCasovFromDatabase(); //nastavenie  ci sa maju vypisvat aj realne, hrube casy popri zaokruhlenych
        $od = $values['datum_od'];
        $do = $values['datum_do'];
        $osoby = $values['osoby'];
   
        $this->template->osoby = $osoby;//hodime naspet zoznam id osob z formulara
        
        $pole_dochadzka_zaokruhlena = array();
        $pole_raw_dochadzka = array();
        $pole_meno_osoby = array();
        $pole_celkovy_cas_dochadzky = array();
        //ziskanie  dochadzky
        //prebehneme ludi z formulara a vypise len ludi, sablona potom poziada o dchadzku tychto ludi
        foreach ( $osoby as $clovek ){
            //nacitanie mena osoby
            $o = new Osoba($this->database); //definovane objektu osoby
            $o->InitializeFromDatabase($clovek); //inicializacia z databazy 
            $pole_meno_osoby[$clovek] = $o->getMeno();
 
            $this->dochadzka->vycisti(); //vyprazdnime udaje predosleho cloveka
            $this->dochadzka->generuj_raw_dochadzku_cloveka($clovek, $od, $do); //naplni objekt udajmi o raw dochaddzke cloveka
            $this->dochadzka->generuj_zaokruhlenu_dochadzku_cloveka($clovek, $od, $do);//naplni objekt udajmi o dochaddzke cloveka
            $pole_dochadzka_zaokruhlena[$clovek]= $this->dochadzka->getPoleDochadzky(); //zaokruhli dochadzku podla nastaveneho zaokruhlovania
            $pole_raw_dochadzka[$clovek] = $this->dochadzka->getPoleRawDochadzky();
            $this->dochadzka->sumarizuj(); //spocitame celkovy cas za obdobie
            $temp_celkovy_cas = $this->dochadzka->getCelkovyCasDochadzky(); //ziskame hodnotu v DateInterval objekte
            if (isset($temp_celkovy_cas)){
                $temp_hodiny = ($temp_celkovy_cas->days*24) + ($temp_celkovy_cas->h); //pocet hodin
                $temp_minuty = $temp_celkovy_cas->i; //pocet minut
            } else {
                $temp_hodiny = 0;
                $temp_minuty = 0;
            }
            $pole_celkovy_cas_dochadzky[$clovek]["hodiny"] = $temp_hodiny;
            $pole_celkovy_cas_dochadzky[$clovek]["minuty"] = $temp_minuty;
        }//end foreach
        
        $this->template->meno_osoby = $pole_meno_osoby;
        $this->template->posts_zaokruhlena_dochadzka = $pole_dochadzka_zaokruhlena;
        $this->template->posts_raw_dochadzka = $pole_raw_dochadzka;
        $this->template->celkovy_cas_dochadzky = $pole_celkovy_cas_dochadzky;
        $this->template->vypisovat_realne_casy = $this->dochadzka->getVypisRealCasov(); //priznak, ze ci budemem vypisovat aj realne casy
    }
    
   /*
    *  formular na nastavenie sposobu vypisovania dochadzky
    */
    protected function createComponentNastavenieForm() {
        //uvodne nastavovacky
        $this->dochadzka->setUserId( $this->getUser()->id );
        $this->dochadzka->setZaokruhlovanieFromDatabase(); //nastavenie predvoleneho zaokruhlovania casov prichodov a odchodov
        $this->dochadzka->setToleranciaFromDatabase(); //nastavenie predvolenej tolerancie neskorzch prichodov
        $this->dochadzka->setVypisRealCasovFromDatabase(); //nastavenie  ci sa maju vypisvat aj realne, hrube casy popri zaokruhlenych
        
        $form = new Form;
        
        $items = array(
            '0' => $this->translator->translate('ui.form.items_array_none'),
            '1' => $this->translator->translate('ui.form.items_array_minutes'),
            '15' => $this->translator->translate('ui.form.items_array_15minutes'),
            '30' => $this->translator->translate('ui.form.items_array_30minutes'),
            '60' => $this->translator->translate('ui.form.items_array_1hour')
        );
        
        $tolerancia = array ( '15' , '30', '60' ); //hodnoty pri ktorzch sa bude zobrazovat textove pole na toleranciu prichodu
        
        $form->addRadioList('zaokruhlenie', $this->translator->translate('ui.form.zaokruhlovanie') , $items)
            ->setDefaultValue($this->dochadzka->getZaokruhlovanie())
            ->addCondition($form::EQUAL, $tolerancia)
            ->toggle('text_late_arrival');
            
        
        //pridame do formulara toleranciu neskoreho prichodu 0 - 10 minut
        //ale len v pripade, ze radioLis zaokruhlovania bude nastaveny na 15, 30, alebo 60 minut
        $form->addText('late_arrival', $this->translator->translate('ui.form.late_arrival') )
            ->setRequired(true)
            ->addRule(Form::INTEGER, $this->translator->translate('ui.form.late_arrival_integer'))
            ->addRule(Form::RANGE, $this->translator->translate('ui.form.late_arrival_range'), [0, 10])
            ->setDefaultValue( $this->dochadzka->getTolerancia() )
            ->setOption('description', $this->translator->translate('ui.form.late_arrival_explanation') )
            ->setOption('id', 'text_late_arrival');
            
        
       $times_options = array (
           '1' => $this->translator->translate('ui.form.yes'), 
           '0' => $this->translator->translate('ui.form.no')
       );
       $form->addRadioList('real_times',$this->translator->translate('ui.form.real_times')  , $times_options  )
            ->setDefaultValue(  $this->dochadzka->getVypisRealCasov()  );
        
        $form->addSubmit('send', $this->translator->translate('ui.form.save'));
        $form->onSuccess[] = [$this, 'nastavenieFormSubmitted']; //spracovanie formulara bude mat na starosti funckia tejto triedy s nazvom: pridajRFIDFormSubmitted
        
        return $form;
    }
    
    public function nastavenieFormSubmitted( $form, $values ) {
        //uvodne nastavovacky
        $this->dochadzka->setUserId( $this->getUser()->id );
        try {
            $this->dochadzka->updateNastavenie ($values);
            $this->flashMessage($this->translator->translate('ui.message.change_success'), 'alert alert-success');
        } catch (\ErrorException $e){
             $this->flashMessage($this->translator->translate('ui.message.change_fail'), 'alert alert-warning'); // informování uživatele o chybě
        }
        
    }
    
    /*
    *  formular na nstavenie schemy pracovnej doby
    */
    public function createComponentNastaveniePracovnejDobyForm() {
        //uvodne nasavovacky   
        $pracovna_doba = new \App\Model\PracovnaDoba($this->database, $this->getUser()->id  );
        $pracovna_doba->setPocetSmienFromDatabase();
        $pracovna_doba->loadPolePracovnejDoby();
        $pocet_smien = $pracovna_doba->getPocetSmien();
                
        $form = new Form;
        
        $zoznam_doby = array (
            '0' => $this->translator->translate('ui.form.standard'),
            '1' => $this->translator->translate('ui.form.shifts'),
            '2' => $this->translator->translate('ui.form.all_day')
        );
        
        $form->addRadioList('prac_doba', $this->translator->translate('ui.form.prac_doba') , $zoznam_doby)
            ->addCondition($form::EQUAL, '1')
            ->toggle('2_smeny')
            ->endCondition()
            ->addCondition($form::EQUAL, '2')
            ->toggle('2_smeny')
            ->toggle('3_smeny')
            ->endCondition();
        $form['prac_doba']->setDefaultValue( $pocet_smien );
        
        $form->addText('prichod1', $this->translator->translate('ui.prichod'))
                ->setAttribute('class', 'form-control');
        
        $form->addText('odchod1', $this->translator->translate('ui.odchod'))
                ->setAttribute('class', 'form-control');
        
        $form->addText('prichod2', $this->translator->translate('ui.prichod'))
                ->setAttribute('class', 'form-control');
        
        $form->addText('odchod2', $this->translator->translate('ui.odchod'))
                ->setAttribute('class', 'form-control');
        
        $form->addText('prichod3', $this->translator->translate('ui.prichod'))
                ->setAttribute('class', 'form-control');
        
        $form->addText('odchod3', $this->translator->translate('ui.odchod'))
                ->setAttribute('class', 'form-control');
        
        if ( $pracovna_doba->getPolePracovnejDoby() ) { //ak uz mame definovanu pracovnu dobu, tak nastavime vstupne hodnoty
            $form['prichod1']->setDefaultValue( $pracovna_doba->getPrichod1()->format("%H:%I") );
            $form['odchod1']->setDefaultValue( $pracovna_doba->getOdchod1()->format("%H:%I") );
            $form['prichod2']->setDefaultValue( $pracovna_doba->getPrichod2()->format("%H:%I") );
            $form['odchod2']->setDefaultValue( $pracovna_doba->getOdchod2()->format("%H:%I") );
            $form['prichod3']->setDefaultValue( $pracovna_doba->getPrichod2()->format("%H:%I") );
            $form['odchod3']->setDefaultValue( $pracovna_doba->getOdchod3()->format("%H:%I") );
        }
        
        $form->addSubmit('send', $this->translator->translate('ui.form.save'));
        $form->onSuccess[] = [$this, 'nastaveniePracovnejDobySubmitted']; //spracovanie formulara bude mat na starosti funckia tejto triedy s nazvom: pridajRFIDFormSubmitted
        
        return $form;
    }
    
    public function nastaveniePracovnejDobySubmitted( $form, $values ){
        $pocet_smien = $values['prac_doba']; 
        
        $pole_pracovnej_doby = array (
            'prichod1' => $values['prichod1'],
            'odchod1' => $values['odchod1'],
            'prichod2' => $values['prichod2'],
            'odchod2' => $values['odchod2'],
            'prichod3' => $values['prichod3'],
            'odchod3' => $values['odchod3']
        );
        
        //uvodne nasavovacky   
        $pracovna_doba = new \App\Model\PracovnaDoba($this->database, $this->getUser()->id  );
        try {
            $pracovna_doba->updatePracovnaDoba($pocet_smien, $pole_pracovnej_doby);
            $this->flashMessage($this->translator->translate('ui.message.change_success'), 'alert alert-success');
        } catch (Exception $ex) {
            $this->flashMessage($this->translator->translate('ui.message.change_fail'), 'alert alert-warning'); // informování uživatele o chybě
        }
        
    }
    
}