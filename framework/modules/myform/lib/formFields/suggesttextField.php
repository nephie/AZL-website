<?php

class suggesttextField extends formField {

	protected $callbackcontroller;
	protected $callbackfunction;

	public function suggesttextField($callbackcontroller, $callbackfunction , $name , $label, $defaultValue = '' , $validator = '',$extra = ''){
		$this->callbackcontroller = $callbackcontroller;
		$this->callbackfunction = $callbackfunction;

		parent::__construct($name , $label, $defaultValue , $validator,$extra);
	}

	public function getSuggestrequest(){
		return new ajaxrequest('myform','updatesuggestfield' , array('callbackcontroller' => $this->callbackcontroller , 'callbackfunction' => $this->callbackfunction));
	}
}

?>