<?php

namespace App\Presenters;

use Nette;


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
    
    function __construct(Nette\Database\Context $database) {
        $this->database = $database;
    }
    
    protected function beforeRender()
    {
        parent::beforeRender();
        $this->template->activeLocale = $this->translator->getLocale();
    }
       
}
