<?php

class mygridform extends form {
	public function __construct($parameters,$gridid,$type,$controller = '',$action = ''){
		if($controller == '' || $action == '') {
			$trace = debug_backtrace();
		}

		if($action == '') {
			$action = $trace[1]['function'];
		}

		if($controller == ''){
			$controller = str_replace('Controller' , '' , $trace[1]['class']);
		}

		$origcontr = $controller;
		$origaction = $action;

		$controller = 'mygrid';
		$action = $type . 'request';

		parent::form($parameters,$controller,$action);

		$this->addField(new hiddenField('controller',$origcontr));
		$this->addField(new hiddenField('action',$origaction));
		$this->addField(new hiddenField('-gridid-',$gridid));
	}
}

?>