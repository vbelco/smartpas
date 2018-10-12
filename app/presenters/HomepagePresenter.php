<?php

namespace App\Presenters;

use Nette;
use App\Model\Uzivatel;
use App\Model\Dochadzka; //pripojime triedu
use App\Model\Osoba; //pripojime triedu 


class HomepagePresenter extends BasePresenter
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
        if ( $this->getUser()->isLoggedIn() ) { //len pre prihlasenych
            $this->dochadzka->setUserId( $this->getUser()->id );
            $this->dochadzka->initialize();//natiahnutie najnovsich udajov z Log tabulky 
            $this->template->posts = $this->dochadzka->getDochadzka(10); //poslednych 10 zaznamov
        }
      
    }//end function renderDefault
    
}
