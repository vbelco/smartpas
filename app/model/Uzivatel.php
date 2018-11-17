<?php
/*
trieda reprezentujuca uzivatela
  */

namespace App\Model;

use Nette;
use Kdyby\Translation;
use App\Model\Organizacia;

class Uzivatel extends Nette\Object
{
    /** @persistent */
    public $locale;

     /** @var \Kdyby\Translation\Translator */
    protected $translator;
    
    /**
     * @var Nette\Database\Context
     */
    private $database;
    
    private $id; //identifikcne uzivatela
    private $name; //meno uzivatela
    private $role; //rola uzivatela
    private $email; //registracny email uzivatela
    private $organizacia_id; //ukazovatel na organiaciu k uzivatelovi
    private $plan_id; //ukazovatel na plan ktory ma dany uzivatel aktivovany
    
    
    function __construct(Nette\Database\Context $database, Translation\Translator $translator)
    {
        $this->database = $database;
	$this->translator = $translator;
    }
    
    public function getId()  { return $this->id; }
    public function getName() { return $this->name; }
    public function getRole () { return $this->role; }
    public function getEmail() {return $this->email; }
    public function getOrganizaciaid() { return $this->organizacia_id; }
    public function getPlanId() { return $this->plan_id; }
    public function getPlanName() {
        try{
            $row =  $this->database->table('plans')->get( $this->plan_id );
            if ($row) { return $row->nazov; }
            else return "";
        } catch ( Nette\Database\ConnectionException $e ){
            throw new \ErrorException;
        }
    }
    
    public function setName($name) {$this->name = $name; }
    public function setRole ($role) {$this->role = $role; }
    public function setEmail ($email) { $this->email = $email; }
    public function setOrganizaciaId ($org_id) { $this->organizacia_id = $org_id; }
    public function setPlanId($plid) { $this->plan_id = $plid; }
    
    public function saveNameToDatabase ($name) {
        try{
            $this->name = $name; //najskor zmenime meno v objekte
            //nasledne ho updatneme v databaze
            $this->database->table('users')
                ->where('id', $this->id) // must be called before update()
                ->update([ 'name' => $this->name  ]);
        } catch ( Nette\Database\ConnectionException $e ){
            throw new \ErrorException;
        }
    }
    
    public function saveRoleToDatabase ($role) {
        try{
            $this->database->table('users')
                ->where('id', $this->id) // must be called before update()
                ->update([ 'role' => $this->role  ]);
        } catch ( Nette\Database\ConnectionException $e ){
            throw new \ErrorException;
        }
    }
    
    public function saveOrganizaciaIdToDatabase(){
        try{
            $this->database->table('users')
                ->where('id', $this->id) // must be called before update()
                ->update([ 'organizacia_id' => $this->organizacia_id  ]);
        } catch ( Nette\Database\ConnectionException $e ){
            throw new \ErrorException;
        }
    }
    
    public function loadUserFromDatabase ($id){
        try {
            $row = $this->database->table('users')->get($id);
            if ($row){ //je taka osoba v databaze
                $this->id = $row->id;
                $this->name = $row->name;
                $this->role = $row->role;
                $this->email = $row->email;
                $this->organizacia_id = $row->organizacia_id;
                $this->plan_id = $row->plan_id;
            }
            else { //osoba sa v databaze nenachadza
                $this->id = NULL;
                $this->name = "";
                $this->role = "";
                $this->email = "";
                $this->organizacia_id = NULL;
                $this->plan_id = NULL;
            }
        } catch (Exception $ex) {
            throw new \ErrorException;
        }  
    }
    
    public function savePasswordToDatabase ($password) {
        try{
            $this->database->table('users')
                ->where('id', $this->id) // must be called before update()
                ->update([ 'password' => \Nette\Security\Passwords::hash($password)  ]);
        } catch ( Nette\Database\ConnectionException $e ){
            throw new \ErrorException;
        }
    }
    
}
