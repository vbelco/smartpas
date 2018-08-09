<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Model\Osoba; //pripojime triedu Osoby
use App\Model\Uzivatel;


class OsobyPresenter extends BasePresenter
{
    /** @var Osoba */
    private $osoba; //trieda Osoba
    
    public function __construct(Nette\Database\Context $database, Uzivatel $uzivatel, Osoba $os) {
        parent::__construct($database, $uzivatel);
        $this->osoba = $os;
    }
    
    public function renderDefault()
    {
        $this->template->posts = $this->database->table('people')
            ->where('users_id = ?', $this->getUser()->id )
            ->where("active = 1")
            ->order('meno ASC')
            ->limit(10);
    }//end function render default
    
    public function actionEdituj($osoba_id){
        $this->osoba->InitializeFromDatabase( $osoba_id ); //nacitame informacie z databzy
        $this->template->osoba_id = $osoba_id;
    }
    
    public function actionDeaktivuj($osoba_id) {
        $this->osoba->InitializeFromDatabase( $osoba_id ); //nacitame informacie z databzy
        $this->template->meno = $this->osoba->getMeno(); //prenesieme do sablony meno osoby
        $this->template->osoba_id = $osoba_id; //prenos id osoby do sablony
        //musime at kontrolu na priradenia osoby k RFIDkam
        $this->template->rfidky = $this->osoba->priradeneRfid();
        
        //pokial bolo potvrdene, tak ho deaktivujeme, teda nastavime priznak active=0
        if ( $this->getParameter('proceed') ){
            if ( $this->osoba->deaktivuj() ){
                $this->flashMessage($this->translator->translate('ui.message.change_success'),'alert alert-success');
                $this->redirect('Osoby:default');
            }
            else {
                $this->flashMessage($this->translator->translate('ui.message.change_fail'), 'alert alert-warning');
                $this->redirect('Osoby:default');
            }
        }//end if proceed = 1
    }
    
    protected function createComponentOsobaForm() {
        $form = new Form;
        $form->addText('meno', $this->translator->translate('ui.form.name').':')
                ->addRule(Form::FILLED, $this->translator->translate('ui.message.required_name') )
                ->addCondition(Form::FILLED);
        
        $form->addSubmit('send', $this->translator->translate('ui.form.save'));
        
        $postId = $this->getParameter('osoba_id'); //skusime ziskat hodnotu parametra id osoby, ak ju mame tak idemem editovat a nie pridavat
        if ($postId){ //ideme editovat, tak hodime prednastavene hodnoty
            $form->setDefaults([//nastavenie hodnot
                'meno' => $this->osoba->getMeno()
                ]);
        }
        
        $form->onSuccess[] = [$this, 'osobaFormSubmitted']; //spracovanie formulara bude mat na starosti funckia tejto triedy s nazvom: pridajRFIDFormSubmitted
        return $form;
    }
    
    public function osobaFormSubmitted( $form, $values) { //spusti metodu na spracovanie registracneho formulara
        $active_user_id = $this->getUser()->id;
        $postId = $this->getParameter('osoba_id'); //skusime ziskat hodnotu id, ze ci ju nahodou nejden editovat,
        if ($postId){
            $this->osoba->InitializeFromDatabase( $postId ); //nacitame informacie z databzy
            $this->osoba->setValuesFromFormular($values); //zmenime udaje v objekte osoba
            $this->osoba->updateToDatabase(); // naspet ulozime do databazy
            $this->flashMessage( $this->translator->translate('ui.message.change_success') );
            $this->redirect('Osoby:default');
        } else { //pridavame noveho
            if ( $this->osoba->InsertOsobuToDatabaseFromFormular($values, $active_user_id ) )
            {
                $this->flashMessage( $this->translator->translate('ui.message.change_success') );
                $this->redirect('Osoby:default');
            }      
        }//end else
    }
    
    /*
     * moznost uivatelov nastavit kontrolu vlastnej dochadzky
     */
    public function actionKontrola($osoba_id){
        $this->osoba->InitializeFromDatabase($osoba_id);//nahrame si do objektu aktualnu osobu, ktoru budeme riesit
        $this->template->meno = $this->osoba->getMeno();
        $this->template->definovane = $this->osoba->getLogin() ? 1 : 0 ; //nastavenie priznaku podla toho ci je logi uz definovany alebo nie
        if (!$this->osoba->getLogin()){ //ak este nemame priradeny login, jeden sivzgenerujeme
            $this->osoba->generujLogin(); //vytvorime si login meno
        }
    }
    
    /*
     * formular na vztvorenie moznosti odoby kontrolvat si svoju dochadzku
     */
    protected function createComponentKontrolaForm() {
        $form = new Form;
        $form->addText('login', 'login:')
                ->setOption('description', '-Vygenerovane prihlasovacie meno osoby-' )
                ->setDisabled(true)
                ->setDefaultValue( $this->osoba->getLogin() );
        $form->addHidden('prihl_meno', $this->osoba->getLogin() ); //musim cez hidden, pretoze ked je addtext nastavenz ako diabled, tak neprenasa hodnotu
        
        $form->addPassword('password', '-Nove heslo:-')
            ->setOption('description', '-Heslo musi mat minimalne 6 znakov-' )
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
        
        $form->addSubmit('send', $this->translator->translate('ui.form.save'));
        $form->onSuccess[] = [$this, 'kontrolaFormSubmitted']; //spracovanie formulara bude mat na starosti funckia tejto triedy s nazvom: pridajRFIDFormSubmitted
        return $form;
    }
    
    public function KontrolaFormSubmitted( $form, $values ){
        try { 
            $this->osoba->saveLoginToDatabase($values->prihl_meno);
            $this->osoba->savePasswordToDatabase($values->password);
            $this->redirect('Osoby:default');
            $this->flashMessage("-Zmeny sa podarili-", "alert alert-success" );
        } catch (Exception $ex) {
            $this->flashMessage("-Nepodarilo sa zmenit heslo-", "alert alert-warning" );
        }    
    }
}