<?php

class timepickerField extends formField {


	public function __construct($name , $label, $defaultValue = '' , $validator = '',$extra = ''){
		$defaultValue = ($defaultValue == '')?time():$defaultValue;
		parent::__construct($name , $label, $defaultValue , $validator , $extra);

	}

	public function loaddatepicker(){
		$response = responseLib::getInstance();

		
			$response->script("setTimeout(\"my_timepicker.attach();\",500);");
		
	}
}

?>