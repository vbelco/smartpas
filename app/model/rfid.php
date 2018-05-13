<?php
/*
trieda reprezentujuca RFID klucenku, kartu a pod....
  */

namespace App\Model;

use Nette;

class RFID extends Nette\Object
{
    /**
     * @var Nette\Database\Context
     */
    private $database;
    /** @var Osoba */
    private $clovek; //trieda reprezentujuca osobu, ktora je napojena na tuto rfidku
    private $id; //id hodnota rfid z databazy
    private $rfid_number; //cislo rfid
    
    function __construct(Nette\Database\Context $database, Osoba $osoba)
    {
        $this->database = $database;
        $this->clovek = $osoba;  
    }
    
    public function InsertRfidToDatabaseFromFormular ($values, $active_user_id){
        
        $row = $this->database->table('rfid')->insert([
            'number' => $values->number, //cislo rfid, ktore sa zadavalo do formulara
            'users_id' => $active_user_id, //komu patri rfidko
            'people_id' => NULL //ktoremu cloveku je priradene, defaultne 0, teda nikomu
        ]); 
        
        return $row;
    }
    
    public function InsertRfidToDatabase($values){
        try {
            $row = $this->database->table('rfid')->insert($values);
            return $row;
        }
        catch (Nette\Neon\Exception $e) {
            throw $e;
        }
    }
    
    public function updateRfidToDatabaseFromFormular($values, $rfid_id){
        $row = $this->database->table('rfid')->get($rfid_id);
        $row->update($values);
    }
    
    public function updateRfidToDatabase(){
        $row = $this->database->table('rfid')->get( $this->id );
        $values = array(
           'people_id' => $this->clovek->getId(),
           'number' => $this->rfid_number
        );
        return $row->update($values);
    }
    
    // model
    public function getAllRfidByUser($user_id)
    {
        return $this->database->table('rfid')
                                ->where("users_id = ?", $user_id ) //natiahneme len rfidky prihlaseneho uzivatelas
                                ->where("active = 1")
                                ->limit(10);  
    }
    
    public function deaktivuj (){
        $row = $this->database->table('rfid')->get( $this->id );
        $values = array(
           'active' => 0
        );
        return $row->update($values);
    }
    
    public function InitializeFromDatabase ($rfid)
    {
        $row = $this->database->table('rfid')->get($rfid);
        if ($row){
            $this->rfid_number = $row->number;
            $this->id = $row->id;
            $this->clovek->InitializeFromDatabase ( $row->people_id );
        } else {
            $this->rfid_number = null;
        }
    }
    
    public function priradOsobu($id_osoby)
    {
        $this->clovek->InitializeFromDatabase($id_osoby); //updatneme objekt novou osobou
        $this->saveChangeAssigmentToDatabase(); //ulozi zmenu piradenia osoby k rfid do databazy
        return $this->updateRfidToDatabase();
    }
    
    //zisti ci RFIDka ma nejaku priradenu osobu
    // ak ano, tak vrati riadok na tuto osobu
    // ak nie, vrati NULL
    public function priradenaOsoba(){ //zisti ci RFIDka ma nejaku priradenu osobu
        $row =  $this->database->table('people')->get( $this->clovek->getId() );
        return $row;
    }
    
    public function getNumber()
    {
        return $this->rfid_number;
    }
    public function getOsoba()
    {
        return $this->clovek;
    }
    
    //funkcia ulozi zmenu priradenia osoby k rfidke do databazy 
    public function saveChangeAssigmentToDatabase()
    {
        $values = array(
           'people_id' => $this->clovek->getId(),
           'rfid_id' => $this->id
        );
        
        return $this->database->table('rfid_to_people_history')->insert($values);
    }
}
