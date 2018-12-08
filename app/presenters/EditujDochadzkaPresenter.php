<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\Application\UI\Multiplier;
use App\Model\DochadzkaOsoby; //pripojime triedu 
use App\Model\Osoba; //pripojime triedu 
use App\Model\Uzivatel;

class EditujDochadzkaPresenter extends BasePresenter
{
    
    /** @var DochadzkaOsoby */
    public $dochadzkaOsoby; //trieda dochadzky, vytvara sa az po odoslani formulara
    
    private $od;  //pre formular datumu od
    private $do;   //pre formular do
    
    /** @var Osoba */ 
    public $osoba;  //trieda osoby
    
    public function __construct(Nette\Database\Context $database, Uzivatel $uzivatel) {
        parent::__construct($database, $uzivatel);
        $this->osoba = new Osoba($this->database); //definovane objektu osoby
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
        
        $form->addRadioList('osoba', $this->translator->translate('ui.form.choose_1_person') ,$pole)
                ->setRequired( $this->translator->translate('ui.message.choose_least_1_person') );
        
        $form->addSubmit('send', $this->translator->translate('ui.form.show') );
        $form->onSuccess[] = [$this, 'prehladFormSubmitted']; //spracovanie formulara bude mat na starosti funckia tejto triedy s nazvom: pridajRFIDFormSubmitted
        
        $form->setDefaults([
            'datum_od' => $this->od,
            'datum_do' => $this->do,
            'osoba' => $this->osoba->id
        ]);
        
        return $form;
    }//end function createComponent PrehladForm
    
    public function prehladFormSubmitted($form , $values){
        //ulozime si udaje z formulara
        $this->od = $values['datum_od'];
        $this->do = $values['datum_do'];
        //nacitanie osoby
        $this->osoba->InitializeFromDatabase($values["osoba"] ); //inicializacia z databazy 
        //vytvorenie dochadzky
        $this->dochadzkaOsoby = new DochadzkaOsoby( $this->database, $this->user->id, $this->osoba->getId() );
        $this->dochadzkaOsoby->nacitaj($this->od, $this->do);   
    }//end function prehladForm Submitted
    
    /* funckia na vztvorenie editacneho formulara */
    protected function createComponentEditPrichodForm() {
        return new Multiplier(function ($id) { //predavame id riadku s dochadzkou v datbaze
            $form = new Nette\Application\UI\Form;
            $form->addText('prichod', '')
                    ->setRequired();
            $form->addHidden('id', $id);
            $form->addHidden('datum_od', $this->od);
            $form->addHidden('datum_do', $this->do);
            $form->addHidden('osoba', $this->osoba->id);
            $form->addSubmit('send', 'Zmeň');
            $form->onSuccess[] = [$this, 'editPrichodFormSubmitted']; //spracovanie formulara bude mat na starosti funckia tejto triedy s nazvom: pridajRFIDFormSubmitted
        return $form;
        });
    }
    
    /* funckia na vztvorenie editacneho formulara */
    protected function createComponentEditOdchodForm() {
        return new Multiplier(function ($id) {
            $form = new Nette\Application\UI\Form;
            $form->addText('odchod', '')
                    ->setRequired();
            $form->addHidden('id', $id);
            $form->addHidden('datum_od', $this->od);
            $form->addHidden('datum_do', $this->do);
            $form->addHidden('osoba', $this->osoba->id);
            $form->addSubmit('send', 'Zmeň');
            $form->onSuccess[] = [$this, 'editOdchodFormSubmitted'];
        return $form;
        });
    }
    
    public function editPrichodFormSubmitted($form , $values){
        $this->od = $values['datum_od'];
        $this->do = $values['datum_do'];
        $osoba_id = $values["osoba"];
        $hodnota_prichodu = $values["prichod"];
        $zaznam_id = $values["id"];
        //nacitanie osoby
        $this->osoba = new Osoba($this->database); //definovane objektu osoby
        $this->osoba->InitializeFromDatabase($osoba_id); //inicializacia z databazy 
        //vytvorenie dochadzky
        $this->dochadzkaOsoby = new DochadzkaOsoby( $this->database, $this->user->id, $this->osoba->getId() );
        //upravenie hodnoty dochadzky
        $this->dochadzkaOsoby->storeDochadzka($zaznam_id, 'prichod', $hodnota_prichodu);
        //nacitanie dochadzky cloveka v danom romedzi
        $this->dochadzkaOsoby->nacitaj($this->od, $this->do); 
    }
    
    public function editOdchodFormSubmitted($form , $values){
        //prenos hodnot z formulara
        $this->od = $values['datum_od'];
        $this->do = $values['datum_do'];
        $osoba_id = $values["osoba"];
        $hodnota_odchodu = $values["odchod"];
        $zaznam_id = $values["id"];
        //nacitanie osoby
        $this->osoba = new Osoba($this->database); //definovane objektu osoby
        $this->osoba->InitializeFromDatabase($osoba_id); //inicializacia z databazy 
        //vytvorenie dochadzky
        $this->dochadzkaOsoby = new DochadzkaOsoby( $this->database, $this->user->id, $this->osoba->getId() );
        //upravenie hodnoty dochadzky
        $this->dochadzkaOsoby->storeDochadzka($zaznam_id, 'odchod', $hodnota_odchodu);
        //nacitanie dochadzky cloveka v danom romedzi
        $this->dochadzkaOsoby->nacitaj($this->od, $this->do); 
    }
    
    /* funckia na vypisanie stranky editacie  */
    public function renderDefault() {
        parent::renderDefault();
        if (isset( $this->osoba )){
            $this->template->osoba = $this->osoba;
        }
        if (isset($this->dochadzkaOsoby)) {
            $this->template->dochadzkaOsoby = $this->dochadzkaOsoby;
        }//end function renderdefault
    }
 
}