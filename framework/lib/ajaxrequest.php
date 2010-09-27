<?php

class ajaxrequest extends getandsetLib {

	protected $controller;
	protected $action;
	protected $parameter;

	public function ajaxrequest($controller, $action , $parameter = array()){
		$this->controller = $controller;
		$this->action = $action;
		$this->parameter = $parameter;
	}
}

?>