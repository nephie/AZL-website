<?php

class checkboxField extends formField {

	protected $selected;

	public function checkboxField($name , $label, $value, $selected = false, $validator = '', $extra){
		$this->selected = $selected;
		parent::formField($name,$label,$value,$validator,$extra);
	}

}

?>