<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * test
 */

namespace App\Model;

use Nette;
/**
 * Description of Osoba
 *
 * @author user
 */
class Osoba extends Nette\Object
{
    /**
     * @var Nette\Database\Context
     */
    private $database;
    
    private $id;
    private $meno; //
    private $active; // 

    function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }
    
    public function InsertOsobuToDatabaseFromFormular ($values, $active_user_id){
        
        $row = $this->database->table('people')->insert([
            'meno' => $values->meno, //meno osoby, ktore sa zadavalo do formulara
            'active' => '1', //ci je osoba aktivna v systeme
            'users_id' => $active_user_id //priradenie uctu ku ktoremu je osoba priradena
        ]); 
        
        return $row;
    }
    
    public function InitializeFromDatabase($osoba_id){
        $row = $this->database->table('people')->get($osoba_id);
        if ($row){ //je taka osoba v databaze
            $this->id = $row->id;
            $this->meno = $row->meno;
            $this->active = $row->active;
        }
        else { //osoba sa v databaze nenachadza
            $this->id = NULL;
            $this->meno = "";
            $this->active = 0;
        }
    }
    
    public function updateToDatabase (){
        $row = $this->database->table('people')->get( $this->id ); //nacitame riadok uzivatela
        $values = array (
            'meno' => $this->meno,
            'active' => $this->active
        );
        $row->update($values);    
    }
    
    public function deaktivuj (){
        $row = $this->database->table('people')->get( $this->id );
        $values = array(
           'active' => 0
        );
        return $row->update($values);
    }
    
    public function priradeneRfid(){
        $row =  $this->database->table('rfid')
                ->where('people_id = ?', $this->id);
        if ( $row->count() ) return $row;
        else return NULL;
    }
    
    public function getMeno() {
        return $this->meno;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function setValuesFromFormular($values){
        $this->meno = $values->meno;
    }
}