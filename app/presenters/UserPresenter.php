<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Model\Uzivatel;
use App\Model\Organizacia;

class UserPresenter extends BasePresenter
{  
    public function __construct(Nette\Database\Context $database, Uzivatel $uzivatel) {
        parent::__construct($database, $uzivatel);
    }
    
    public function renderDefault() //defaultny vypis nasich rfidiek
    {
        parent::renderDefault(); //zavolame si nadriadeneho na globalne veci
    }
    
    protected function createComponentUserFormEdit() 
    {
        $this->uzivatel->loadUserFromDatabase( $this->getUser()->id );
        $form = new Form;
        $form->addText('email', '-Registracny email-')
                ->setDisabled()
                ->setDefaultValue( $this->uzivatel->getEmail() );
        $form->addText('name', '-Meno-:' )
                ->setDefaultValue( $this->uzivatel->getName() );
        
        $form->addSubmit('send', $this->translator->translate('ui.form.save') );
        $form->onSuccess[] = [$this, 'UserFormSubmitted']; //spracovanie formulara bude mat na starosti funckia tejto triedy s nazvom: pridajRFIDFormSubmitted
        

        return $form;
    }
    
    public function UserFormSubmitted( $form, $values ){
        try {
            $this->uzivatel->saveNameToDatabase($values->name); //ulozime si uzivatela do databazy
            $this->flashMessage("-Zmeny sa podarili-", "alert alert-success" );
        } catch (Exception $ex) {
            $this->flashMessage("-Nepodarilo sa zmenit meno-", "alert alert-warning" );
        }    
    }
//----------------------------------------------------------------------------------------------------   
    protected function createComponentUserFormPasswordEdit() 
    {
        $this->uzivatel->loadUserFromDatabase( $this->getUser()->id );
        $form = new Form;
        
        $form->addPassword('password', '-Nove heslo:-')
            ->addCondition(Form::FILLED)
            ->addRule(Form::MIN_LENGTH, '-Položka %label musí obsahovat min. %d znaků-', 6)
            ->addRule(Form::MAX_LENGTH, '-Položka %label může obsahovat max. %d znaků-', 255);
        $form['password']->getControlPrototype()->autocomplete('off');
        
        $form->addPassword('password_again', '-Heslo (znovu):-')
            ->setRequired( $this->translator->translate('ui.message.fill_passwd') )
            ->addConditionOn($form["password"], Form::FILLED)
            ->addRule(Form::EQUAL, "-Hesla se musí shodovat!-", $form["password"])
            ->addRule(Form::MIN_LENGTH, '-Položka %label musí obsahovat min. %d znaků-', 6)
            ->addRule(Form::MAX_LENGTH, '-Položka %label může obsahovat max. %d znaků-', 255);
        
        $form->addSubmit('send', $this->translator->translate('ui.form.save') );
        $form->onSuccess[] = [$this, 'PasswordFormSubmitted']; //spracovanie formulara bude mat na starosti funckia tejto triedy s nazvom: pridajRFIDFormSubmitted
        
        return $form;
    }
    
    public function PasswordFormSubmitted( $form, $values ){
        try {
            $this->uzivatel->savePasswordToDatabase($values->password);
            $this->flashMessage("-Zmeny sa podarili-", "alert alert-success" );
        } catch (Exception $ex) {
            $this->flashMessage("-Nepodarilo sa zmenit heslo-", "alert alert-warning" );
        }    
    }
//----------------------------------------------------------------------------------------------------    
    protected function createComponentOrganizaciaFormEdit() 
    {
        $this->uzivatel->loadUserFromDatabase( $this->getUser()->id ); //nahrame uzivatela z databazy a kemu pprisluchajucej organizacie
        if ($this->uzivatel->getOrganizaciaId()){ //uz mame definovanu organizaciu k uzivatelovi
            $organizacia = new Organizacia($this->database, $this->translator); //vytvorime si nulovu organizaciu
            $organizacia->loadFromDatabase( $this->uzivatel->getOrganizaciaId() );
            $nazov = $organizacia->getNazov();
            $adresa = $organizacia->getAdresa();
            $vat = $organizacia->getVAT();
            $mesto = $organizacia->getMesto();
            $psc = $organizacia->getPSC();
            $krajina = $organizacia->getKrajina();
        } else{ //este nemame definovanu organizaciu
            $nazov = "-Uvedte nazov organizacie-";
            $adresa = "-Adresa-";
            $vat = "-ICO-";
            $mesto = "-mesto-";
            $psc = "-PSC-";
            $krajina = "-krajina-";
        }
           
        $form = new Form;
        $form->addText('nazov', '-Nazov-:' )
                ->setDefaultValue( $nazov );
        $form->addText('vat', '-ICO-:' )
                ->setDefaultValue( $vat );
        $form->addText('adresa', '-Adresa-:' )
                ->setDefaultValue( $adresa );
        $form->addText('psc', '-PSC-:' )
                ->setDefaultValue( $psc );
        $form->addText('mesto', '-Mesto-:' )
                ->setDefaultValue( $mesto );
        $form->addText('krajina', '-Krajina-:' )
                ->setDefaultValue( $krajina );

        $form->addSubmit('send', $this->translator->translate('ui.form.save') );
        $form->onSuccess[] = [$this, 'OrganizaciaFormSubmitted']; //spracovanie formulara bude mat na starosti funckia tejto triedy s nazvom: pridajRFIDFormSubmitted
                
        return $form;
    }
    
    public function OrganizaciaFormSubmitted ($form, $values) {
        try {
            $this->uzivatel->loadUserFromDatabase( $this->getUser()->id ); //nahrame uzivatela z databazy a kemu pprisluchajucej organizacie
            if ($this->uzivatel->getOrganizaciaId()){ //uz mame definovanu organizaciu k uzivatelovi
                $organizacia = new Organizacia($this->database, $this->translator); //vytvorime si nulovu organizaciu
                $organizacia->nastavOrganizaciu($this->uzivatel->getOrganizaciaId() , 
                                                $values->nazov, 
                                                $values->vat, 
                                                $values->adresa, 
                                                $values->mesto, 
                                                $values->psc,
                                                $values->krajina);
                $organizacia->updateToDatabase(); //updatneme udaje v databaze
                $this->flashMessage("-Zmeny sa podarili-", "alert alert-success" );
            } else {
                $organizacia = new Organizacia($this->database, $this->translator); //vytvorime si nulovu organizaciu
                $organizacia->nastavOrganizaciu($this->uzivatel->getOrganizaciaId() , 
                                                $values->nazov, 
                                                $values->vat, 
                                                $values->adresa, 
                                                $values->mesto, 
                                                $values->psc,
                                                $values->krajina);
                $row = $organizacia->insertToDatabase(); //vytvorime novu organizaciu v databaze
                $this->uzivatel->setOrganizaciaId( $row->id ); //ulozime si novovytvorenu organizaciu do objektu uzivatel
                $this->uzivatel->saveOrganizaciaIdToDatabase();
                $this->flashMessage("-Zmeny sa podarili-", "alert alert-success" );
            }
        } catch (Exception $ex) {
            $this->flashMessage("-Nepodarilo sa ulozit zmeny-", "alert alert-warning" );
        }
    }
 
}//end class