<?php
/*
trieda reprezentujuca  organizaciu priradenu k uzivatelovi
  */

namespace App\Model;

use Nette;
use Kdyby\Translation;

class Organizacia extends Nette\Object
{
    /** @persistent */
    public $locale;

     /** @var \Kdyby\Translation\Translator */
    protected $translator;
    
    /**
     * @var Nette\Database\Context
     */
    private $database;
    
    private $id; //identifikcne cislo organizacie
    private $nazov; // nazov organizacie
    private $vat; //ic dph
    private $adresa;
    private $mesto;
    private $psc;
    private $krajina;
    

    function __construct(Nette\Database\Context $database, Translation\Translator $translator)
    {
        $this->database = $database;
	$this->translator = $translator;
        $this->id = NULL; //prednastavime si nulovu organizaciu
    }
    /*
     * gettery
     */    
    public function getId() { return $this->id; }
    public function getNazov() { return $this->nazov; }
    
    /*
     * settery
     */
    public function nastavOrganizaciu($id, $nazov, $vat='', $adresa='', $mesto='', $psc='', $krajina=''){
        $this->id = $id;
        $this->nazov = $nazov;
        $this->adresa = $adresa;
        $this->vat = $vat;
        $this->psc = $psc;
        $this->mesto = $mesto;
        $this->krajina = $krajina;
    }
    
    /*
     * funckia nahra uaje o organizacii z databaze do objektu
     */
    public function loadFromDatabase ($id){
        try {
            $row = $this->database->table('organizacia')->get($id);
            if ($row){ //je taka osoba v databaze
                $this->id = $row->id;
                $this->nazov = $row->nazov;
                $this->adresa = $row->adresa;
                $this->vat = $row->vat;
                $this->psc = $row->psc;
                $this->mesto = $row->mesto;
                $this->krajina = $row->krajina;
            }
            else { //organizacia sa v databaze nenachadza
                $this->id = NULL;
            }
        } catch (Exception $ex) {
            throw new \ErrorException;
        }  
    }
    
    /*
     * funkcia na vlozenie novej organizacie do databazy
     * vracia: ActiveRow
     */
    public function insertToDatabase (){
        try{
            $row = $this->database->table('organizacia')->insert([
                'nazov' => $this->nazov,
                'adresa' => $this->adresa,
                'vat' => $this->vat,
                'psc' => $this->psc,
                'mesto' => $this->mesto,
                'krajina' => $this->krajina
            ]);
            return $row;

        } catch (Exception $ex) {
            throw new \ErrorException;
        }
    }
    
    /*
     * funckai na zmenu udajov organizacie v databaze
     * vracia pocet zmenenych riadkov, teda 1
     */
    public function updateToDatabase (){
        try{
            $count = $this->database->table('organizacia')
                ->where('id', $this->id ) // must be called before update()
                ->update([
                    'nazov' => $this->nazov,
                    'adresa' => $this->adresa,
                    'vat' => $this->vat,
                    'psc' => $this->psc,
                    'mesto' => $this->mesto,
                    'krajina' => $this->krajina
                ]);
            return $count;
        } catch (Exception $ex) {
            throw new \ErrorException;
        }
    }
    
    
}
