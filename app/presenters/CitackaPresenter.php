<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\Application\UI\Multiplier;
use App\Model\Citacka; //pripojime triedu RFID
use App\Model\Uzivatel;

class CitackaPresenter extends BasePresenter
{
    /** @var Citacka */
    private $citacka; //trieda rfid
    
    public function __construct(Nette\Database\Context $database, Uzivatel $uzivatel, Citacka $citacka) {
        parent::__construct($database, $uzivatel);
        $this->citacka = $citacka;
    }
    
    public function renderDefault() //defaultny vypis nasich rfidiek
    {
        $this->template->posts = $this->citacka->getAllCitackaByUser( $this->getUser()->id ); //nacitanie citaciek aktivneho uzivatela
    }
    
    protected function createComponentCitackaForm() {
        $form = new Form;
        $form->addText('id', $this->translator->translate('ui.id_reader').':' )
                ->addRule(Form::FILLED, $this->translator->translate('ui.fill_reader_id') )
                ->addCondition(Form::FILLED);
        $form->addText('name', $this->translator->translate('ui.name_reader').':' )
                ->addRule(Form::FILLED, $this->translator->translate('ui.fill_reader_name') )
                ->addCondition(Form::FILLED);
        
        $postId = $this->getParameter('citacka_id'); //skusime ziskat hodnotu id rfidky, ze ci ju nahodou nejden editovat,
        if ($postId){ //ideme editovat, tak hodime prednastavene hodnoty
            $form->setDefaults([//nastavenie hodnot
                'name' => $this->citacka->getName() 
                ]);     
        }
        
        $form->addSubmit('send', $this->translator->translate('ui.form.save') );
        $form->onSuccess[] = [$this, 'CitackaFormSubmitted']; //spracovanie formulara bude mat na starosti funckia tejto triedy s nazvom: pridajRFIDFormSubmitted
        
        return $form;
    }
    
    protected function createComponentCitackaFormEdit() {
        $form = new Form;
        $form->addText('id', $this->translator->translate('ui.id_reader').':' )
                ->setDisabled(true);
        $form->addText('name', $this->translator->translate('ui.name_reader').':' )
                ->addRule(Form::FILLED, $this->translator->translate('ui.fill_reader_name') )
                ->addCondition(Form::FILLED);
        
        $form->setDefaults([//nastavenie hodnot
                'id' => $this->citacka->getId(),
                'name' => $this->citacka->getName() 
                ]);     
        
        $form->addSubmit('send', $this->translator->translate('ui.form.save') );
        $form->onSuccess[] = [$this, 'CitackaFormSubmitted']; //spracovanie formulara bude mat na starosti funckia tejto triedy s nazvom: pridajRFIDFormSubmitted
        
        return $form;
    }
    
    public function CitackaFormSubmitted( $form, $values) { //spusti metodu na spracovanie registracneho formulara
        $active_user_id = $this->getUser()->id;
        $postId = $this->getParameter('citacka_id'); //skusime ziskat hodnotu id rfidky, ze ci ju nahodou nejden editovat,

        if ($postId) { //uz mame, upravujeme
            $this->citacka->updateCitackaToDatabaseFromFormular($values, $postId);
            $this->flashMessage($this->translator->translate('ui.message.change_success'), 'alert alert-success');
            $this->redirect('Citacka:default');
        }
        else { //este nemame, pridavame novu
            if ( $vysl = $this->citacka->InsertCitackaToDatabaseFromFormular($values, $active_user_id ) )
            {
                $this->flashMessage($this->translator->translate('ui.message.add_reader'), 'alert alert-success');
                $this->redirect('Citacka:default');
            }  
        }      
    }//end function RFIDFormSubmitted
    
    public function actionEdituj($citacka_id){
        $this->citacka->InitializeFromDatabase( $citacka_id ); //nacitame informacie z databzy
        $this->template->citacka_id = $citacka_id; //prenos id citacky, ktora sa edituje
    }
    
    public function actionDeaktivuj($citacka_id) {
        $this->citacka->InitializeFromDatabase( $citacka_id ); //nacitame informacie z databzy
        $this->template->number = $this->citacka->getNumber(); //prenesieme do sablony cislo aktualnej rfidky
        $this->template->citacka_id = $citacka_id; //prenos id rfidky do sablony
        
        
        //pokial bolo potvrdene, tak ho deaktivujeme, teda nastavime priznak active=0
        if ( $this->getParameter('proceed') ){
            if ( $this->citacka->deaktivuj() ){
                $this->flashMessage($this->translator->translate('ui.message.change_success'),'bg-success');
                $this->redirect('Citacka:default');
            }
            else {
                $this->flashMessage($this->translator->translate('ui.message.change_fail'), 'alert alert-warning');
                $this->redirect('Citacka:default');
            }
        }//end if proceed = 1
    }
    
    
}