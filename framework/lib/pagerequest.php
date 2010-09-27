<?php

class pagerequest extends getandsetLib {

	protected $pageid;
	protected $parameter;

	public function pagerequest($id , $parameter = array()){
		$this->pageid = $id;
		$this->parameter = $parameter;
	}
}

?>