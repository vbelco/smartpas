<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;
use App\UserManager;


class SignPresenter extends BasePresenter
{
    
    protected function createComponentSignInForm()
    {
        $form = new Form;
        $form->addText('email', $this->translator->translate('ui.form.email').':')
            ->setRequired( $this->translator->translate('ui.message.fill_email') );

        $form->addPassword('password', $this->translator->translate('ui.form.passwd').':' )
            ->setRequired( $this->translator->translate('ui.message.fill_passwd') );

        $form->addSubmit('send', $this->translator->translate('ui.form.login') );

        $form->onSuccess[] = [$this, 'signInFormSucceeded'];
        return $form;
    }
    
    public function signInFormSucceeded($form, $values)
    {
    try {
        $this->getUser()->login($values->email, $values->password);
        $this->redirect('Homepage:');

    } catch (Nette\Security\AuthenticationException $e) {
        $form->addError( $this->translator->translate('ui.message.wrong_passwd'));
    }
    }

    public function actionOut()
    {
        $this->getUser()->logout();
        $this->flashMessage( $this->translator->translate('ui.message.success_logout'), "alert alert-success" );
        $this->redirect('Homepage:');
    }
    
    protected function createComponentRegisterForm() {
        $form = new Form;
        $form->addText('name', $this->translator->translate('ui.form.name') )
                ->setOption('description', $this->translator->translate('ui.message.required' ) )
                ->addRule(Form::FILLED, $this->translator->translate('ui.message.required') )
                ->addRule(Form::FILLED, $this->translator->translate('ui.message.fill_name') );
        $form->addText('email', $this->translator->translate('ui.form.email'), 35)
		->setOption('description', $this->translator->translate('ui.message.required' ) )
                ->setEmptyValue('@')
                ->addRule(Form::FILLED, $this->translator->translate('ui.message.fill_email') )
                ->addCondition(Form::FILLED)
                ->addRule(Form::EMAIL, $this->translator->translate('ui.message.wrong_email') );
        $form->addPassword('password', $this->translator->translate('ui.form.passwd'), 6 )
                ->setOption('description',  $this->translator->translate('ui.message.required' ) .
					    $this->translator->translate('ui.message.passwd_length' , ['letters' => 6] ) )
                ->addRule(Form::FILLED, $this->translator->translate('ui.message.fill_passwd') )
                ->addRule(Form::MIN_LENGTH, $this->translator->translate('ui.message.passwd_min_length', ['letters' => 6]) , 6  );
        $form->addPassword('password2',  $this->translator->translate('ui.form.passwd2') , 6 )
		->setOption('description', $this->translator->translate('ui.message.required' ) )
                ->addConditionOn($form['password'], Form::VALID)
                ->addRule(Form::FILLED, $this->translator->translate('ui.form.passwd2') )
                ->addRule(Form::EQUAL, $this->translator->translate('ui.message.passwd_must_be_equal' ) , $form['password']);
        $form->addSubmit('send', $this->translator->translate('ui.form.register'));
        
        $form->onSuccess[] = [$this, 'registerFormSubmitted'];
        return $form;
    }
    
    public function registerFormSubmitted( $form, $values) { //spusti metodu na spracovanie registracneho formulara 
        $uzivatel = new \App\Model\UserManager($this->database); //vytvorenie uzivatela
        $username = $values->name;
        $email = $values->email;
        $password = $values->password;
        try {
            $uzivatel->add( $username, $email, $password );
            $this->flashMessage( $this->translator->translate('ui.message.successfull_registration' ), "alert alert-success" );
            $this->redirect('Homepage:default');
        } catch (\ErrorException $e) {
            $form["email"]->addError( $this->translator->translate('ui.message.used_email' ) );
            $this->flashMessage( $this->translator->translate('ui.message.NOTsuccessfull_registration' ), "alert alert-warning" );
        }
        
        
    }
}