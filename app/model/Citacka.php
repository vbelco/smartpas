<?php
/*
trieda reprezentujuca RFID klucenku, kartu a pod....
  */

namespace App\Model;

use Nette;
use Kdyby\Translation;

class Citacka extends Nette\Object
{
    /** @persistent */
    public $locale;

     /** @var \Kdyby\Translation\Translator */
    protected $translator;
    
    /**
     * @var Nette\Database\Context
     */
    private $database;
    
    private $id; //identifikcne cislo citacky
    private $name; //pomenovanie citacky, MA BYT TIEZ UNIKATNE aby sa dvakrat neopakovalo u jedneho uzivatela
    private $active; //ze ci je citacka aktivna, ci sa zobrazuje
    

    function __construct(Nette\Database\Context $database, Translation\Translator $translator)
    {
        $this->database = $database;
	$this->translator = $translator;
    }
    
    function InsertCitackaToDatabaseFromFormular ($values, $active_user_id){
        //kontrola na existenciu duplicitneho mena citacky
        try {
            //zavedenie novej citacky do databazy
            $row = $this->database->table('citacka')->insert([
            'id' => $values->id,  //unikatne id cislo citacky
            'name' => $values->name, //cislo rfid, ktore sa zadavalo do formulara
            'users_id' => $active_user_id, //komu patri rfidko            
            ]);    
        } catch (\Kdyby\Doctrine\DuplicateEntryException $e) {
            throw $e;
        }
          
	
        return $row;
    }
    
    public function updateCitackaToDatabaseFromFormular($values, $id){
        $row = $this->database->table('citacka')->get($id);
        $row->update($values);
    }
    
    public function getNumber()
    {
        return $this->id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    
    public function getAllCitackaByUser($user_id)
    {
        return $this->database->table('citacka')
                                ->where("users_id = ?", $user_id ) //natiahneme len citacky prihlaseneho uzivatelas
                                ->where("active = 1")
                                ->limit(50);  
    }
    
    public function InitializeFromDatabase ($id)
    {
        $row = $this->database->table('citacka')->get($id);
        if ($row){
            $this->id = $row->id;
            $this->active = $row->active;
            $this->name = $row->name;
        }
    }
    
     public function deaktivuj (){
        $row = $this->database->table('citacka')->get( $this->id );
        $values = array(
           'active' => 0
        );
        return $row->update($values);
    }
    
}
