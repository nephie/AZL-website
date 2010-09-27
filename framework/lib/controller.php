<?php

class controller {

	protected $response;
	protected $self;
	protected $allowedget = array();

	public function controller($self){
		$this->self = $self;

		if($this->self == ''){
			$trace = debug_backtrace();
			if($trace[1]['object'] instanceof controller){
				$this->self = $trace[1]['object']->getSelf();
			}
		}

		$this->response = responseLib::getInstance();
	}

	public function getSelf(){
		return $this->self;
	}

	public function getAllowedget(){
		return $this->allowedget;
	}
}

?>