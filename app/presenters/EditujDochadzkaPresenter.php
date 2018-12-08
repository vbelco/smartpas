<?php
//halo
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Model\Dochadzka; //pripojime triedu 
use App\Model\Osoba; //pripojime triedu 
use App\Model\Uzivatel;

class EditujDochadzkaPresenter extends BasePresenter
{
    
    /** @var Dochadzka */
    private $dochadzka; //trieda dochadzky
    
    private $od;  //pre formular datumu od
    private $do;   //pre formular do
    private $osoba;  //tireda osoby
    
    public function __construct(Nette\Database\Context $database) {
        parent::__construct($database, $uzivatel);
        $this->osoba = new Osoba($this->database); //definovane objektu osoby
        $this->dochadzka = new Dochadzka($this->database); //definovane objektu dochadzky
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
        
        $form->addRadioList('osoba', $this->translator->translate('ui.form.choose_person') ,$pole)
                ->setRequired( $this->translator->translate('ui.message.choose_least_1_person') );
        
        $form->addSubmit('send', $this->translator->translate('ui.form.show') );
        $form->onSuccess[] = [$this, 'prehladFormSubmitted']; //spracovanie formulara bude mat na starosti funckia tejto triedy s nazvom: pridajRFIDFormSubmitted
        
        return $form;
    }//end function createComponent PrehladForm
    
    public function prehladFormSubmitted($form , $values){
        //ulozime si udaje z formulara
        $this->od = $values['datum_od'];
        $this->do = $values['datum_do'];
        //nacitanie osoby
        $this->osoba->InitializeFromDatabase($values["osoba"] ); //inicializacia z databazy 
        
        
        
        
        
    }//end function prehladForm Submitted
 
}