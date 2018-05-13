<?php

namespace App\Presenters;

use Nette;


class HomepagePresenter extends BasePresenter
{
    public function renderDefault()
    {
        
        if ( $this->getUser()->isLoggedIn() ) {
        $this->template->posts = $this->database->table('log')
                ->where('citacka.users_id = ?', $this->getUser()->id) //odfiltruje len tie zaznamy, ktore patria nasej citacke
                ->where('citacka.active = 1') //zobrazi en aktivne citacky    
            ->order('timestamp DESC')
            ->limit(50);
        }
        
    }
    
}
