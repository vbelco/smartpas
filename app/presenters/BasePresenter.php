<?php

namespace App\Presenters;

use Nette;
use App\Model\Uzivatel;

abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    /** @persistent */
	public $locale;

	/** @var \Kdyby\Translation\Translator @inject */
	public $translator;
    
    /**
     * @var Nette\Database\Context
     */
    public $database;
    
    protected $uzivatel;
    
    
    function __construct(Nette\Database\Context $database, Uzivatel $uzivatel) {
        $this->database = $database;
        $this->uzivatel = $uzivatel;
    }
    
    protected function beforeRender()
    {
        parent::beforeRender();
        $this->template->activeLocale = $this->translator->getLocale();
        
        if ( $this->getUser()->isLoggedIn() ) { //ak je este prihlaseny, nacitame ho
            $this->uzivatel->loadUserFromDatabase( $this->getUser()->id );
            $this->template->meno_uzivatela = $this->uzivatel->getName();
            $this->template->nazov_planu = $this->uzivatel->getPlanName();
        }
    }
    
    protected function renderDefault()
    {
        if ( !($this->getUser()->isLoggedIn()) ) { //ak NIE JE este prihlaseny, presmerujeme ho na prihlasenie
            $this->redirect('Sign:in');
        }
    }
       
}
