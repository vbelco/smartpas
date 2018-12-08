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
    
    public $id;
    public $meno; //
    public $active; // 
    public $login; //login osoby na kontrolu svojej dochadky    

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
            $this->login = $row->login;
        }
        else { //osoba sa v databaze nenachadza
            $this->id = NULL;
            $this->meno = "";
            $this->active = 0;
            $this->login = NULL;
        }
    }
    
    public function updateToDatabase (){
        $row = $this->database->table('people')->get( $this->id ); //nacitame riadok uzivatela
        $values = array (
            'meno' => $this->meno,
            'active' => $this->active,
            'login' => $this->login
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
    
    public function getMeno() { return $this->meno; }  
    public function getId(){ return $this->id; }
    public function getLogin() { return $this->login; }
    
    public function setLogin($login) { $this->login = $login; }
    public function setId($id) { $this->id = $id; }
    
    public function setValuesFromFormular($values){
        $this->meno = $values->meno;
    }
    
    public function savePasswordToDatabase ($password) {
        try{
            $this->database->table('people')
                ->where('id', $this->id) // must be called before update()
                ->update([ 'password' => \Nette\Security\Passwords::hash($password)  ]);
        } catch ( Nette\Database\ConnectionException $e ){
            throw new \ErrorException;
        }
    }
    
    public function saveLoginToDatabase () {
        try{
            $this->database->table('people')
                ->where('id', $this->id) // must be called before update()
                ->update([ 'login' => $this->login]);
        } catch ( Nette\Database\ConnectionException $e ){
            throw new \ErrorException;
        }
    }
    
    public function generujLogin(){
        $temp = preg_replace('/&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml|caron);/i','$1',htmlentities($this->meno));
        $temp = mb_strtolower($temp); //zmeni na male pismena
        $array_temp = explode(" ",$temp); //rozdelime na pole retazcov
        $pocet = count ($array_temp); 
        $temp = $array_temp[$pocet-1]; //posledne priezvisko
        $this->login = $temp; //hodime si pociatocny login do objektu
        $pocet = 1; //pomocny ciselnicek
        while ( !$this->kontrolujJedinecnostLoginu() ){ //bude skusat jedinecnost loginov, kde na konci priezviska rida cislo 1,2,3...
            $this->login = $temp.$pocet;
            $pocet++;
        }
        
            
    }
    
    public function kontrolujJedinecnostLoginu(){
        $row = $this->database->table('people')->where('login = ?', $this->login)->count();// ze ci uz nahodou nemame taky login v databaze
        if ($row == 0 ) return true; //login je deninecny
        else return false; //taky login uz mame
    }
}