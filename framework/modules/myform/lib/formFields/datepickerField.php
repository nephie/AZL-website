<?php

class datepickerField extends formField {

	protected $time;

	public function __construct($name , $label, $time = false ,$defaultValue = '' , $validator = '',$extra = ''){
		$defaultValue = ($defaultValue == '')?time():$defaultValue;
		parent::__construct($name , $label, $defaultValue , $validator , $extra);

		$this->time = $time;
	}

	public function loaddatepicker(){
		$response = responseLib::getInstance();

		if($this->time){
			$response->script("setTimeout(\"my_datepicker_time.attach();\",500);");
		}
		else{
			$response->script("setTimeout(\"my_datepicker.attach();\",500);");
		}
	}
}

?>