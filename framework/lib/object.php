<?php

class object extends getandsetLib implements myaclInterface {
	
	protected $idattr = 'id';

	public function getId(){
		$t = $this->idattr;
		return $this->$t;
	}
	
	public function getIdattr(){
		return $this->idattr;
	}

	public function getMyaclrelated(){
		return array();
	}
}

?>