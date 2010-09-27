<?php

abstract class formField extends getandsetLib {

	protected $validator;
	protected $validateerror;

	protected $id;

	protected $name;
	protected $label;
	protected $defaultvalue;
	protected $value;
	protected $extra;

	protected $form;

	public function formField($name , $label, $defaultValue = '' , $validator = '', $extra = ''){
		$this->name = $name;
		$this->label = $label;
		$this->defaultvalue = $defaultValue;
		$this->extra = $extra;
		if( is_array($validator)){
			$this->validator = $validator;
		}
		elseif($validator != '') {
			$this->validator = array($this->validator);
			array_push($this->validator , $validator);
		}
	}

	public function getId(){
		if($this->id != ''){
			return $this->id;
		}
		else{
			return $this->name;
		}
	}

	function validate($value , $form = null) {
		$this->form = $form;
		$valid = true;
		foreach ($this->validator as $req){
			list($function , $param) = explode(':',$req,2);
			$function = 'validate_' . $function;

			if(!call_user_func(array($this,$function),$param,$value)){
				$valid = false;
			}
		}

		$response = responseLib::getInstance();
		if(!$valid){
			$template = new ui($this);
			$template->assign('validateerror' , $this->validateerror);
			//$response->assign($this->id . '_error' , 'innerHTML' , $template->fetch('validateerror.tpl'),true);
			$response->assign($this->id , 'title' , $template->fetch('validateerror.tpl'));
			$response->script("highlightformfielderror('$this->id')");
		}
		else {
			$response->assign($this->id  , 'title' , '');
			$response->script("removehighlightformfielderror('$this->id')");
		}

		return $valid;
	}

	function validate_required($param , $value){
		if($value == '' || (is_array($value) && count($value) == 0)){
			$this->addValidateerror('Dit veld moet ingevuld worden');
			return false;
		}
		else {
			return true;
		}
	}

	function validate_minlength($param , $value){
		if(strlen($value) < $param){
			$this->addValidateerror('Minimale lengte: ' . $param);
			return false;
		}
		else {
			return true;
		}
	}

	function validate_same($param , $value){
		if($this->form != null){
			$compareValue = $this->form->getFieldvalue($param);
			if($value != $compareValue){
				$this->addValidateerror('De waarden moeten overeenkomen');
				return false;
			}
			else {
				return true;
			}
		}
		else {
			$this->addValidateerror('Could not compare: no form given');
			return false;
		}
	}

	function validate_range($param, $value){
		list($min , $max) = explode('<->' , $param , 2);

		if($min <= $value && $value <= $max){
			return true;
		}
		else {
			$this->addValidateerror('Waarde moet tussen ' . $min . ' en ' . $max . ' liggen');
			return false;
		}
	}

	function validate_min($param,$value){
		if($param <= $value ){
			return true;
		}
		else {
			$this->addValidateerror('Waarde moet meer dan ' . $param . ' zijn');
			return false;
		}
	}

	function validate_max($param,$value){
		if($param >= $value ){
			return true;
		}
		else {
			$this->addValidateerror('Waarde moet minder dan ' . $param . ' zijn');
			return false;
		}
	}

	function validate_numeric($param , $value){
		if(is_numeric($value)){
			return true;
		}
		else {
			$this->addValidateerror('De waarde moet numeriek zijn');
			return false;
		}
	}

	function validate_mac($param, $value){
		if (preg_match('/^[a-f0-9]{2}:[a-f0-9]{2}:[a-f0-9]{2}:[a-f0-9]{2}:[a-f0-9]{2}:[a-f0-9]{2}$/i',$value)){
			return true;
		}
		else {
			$this->addValidateerror('De waarde moet een geldig mac adres zijn, met \':\' als scheidingsteken');
			return false;
		}
		
	}
}

?>