<?php

class Notification {
	private $_id;
	private $_titre;
	private $_type;
	private $_details;
	private $_date;

	// constructeur
	function __construct($donnees){
		$this->hydrate($donnees);
	}

	// hydrater
	public function hydrate(array $donnees){
		foreach ($donnees as $key => $value){
			// On récupère le nom du setter correspondant à l'attribut.
			$newKey = strtolower($key);
			$method = 'set'.ucfirst($newKey);
			// Si le setter correspondant existe.
	    	if (method_exists($this, $method)){
		    	// On appelle le setter.
		    	$this->$method($value);
	    	}
		}
	}

	// getters
	public function id(){
		return $this->_id;
	}
	public function titre(){
		return $this->_titre;
	}
	public function type(){
		return $this->_type;
	}
	public function details(){
		return $this->_details;
	}
	public function date(){
		return $this->_date;
	}

	// setters
	public function setId($id){
		$this->_id = $id;
	}
	public function setTitre($titre){
		$this->_titre = $titre;
	}
	public function setType($type){
		$this->_type = $type;
	}
	public function setDetails($details){
		$this->_details = $details;
	}
	public function setDate($date){
		$this->_date = $date;
	}
}

?>