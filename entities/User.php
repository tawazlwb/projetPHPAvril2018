<?php

class User {
	private $_id;
	private $_nom;
	private $_login;
	private $_password;

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
	public function nom(){
		return $this->_nom;
	}
	public function login(){
		return $this->_login;
	}
	public function password(){
		return $this->_password;
	}

	// setters
	public function setId($id){
		$this->_id = $id;
	}
	public function setNom($nom){
		$this->_nom = $nom;
	}
	public function setLogin($login){
		$this->_login = $login;
	}
	public function setPassword($password){
		$this->_password = $password;
	}
}

?>