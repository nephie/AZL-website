<?php

class passwordField extends formField {

	public function passwordField($name , $label = '', $validator = '', $extra = ''){
		parent::__construct($name , $label, '' , $validator, $extra);
	}

}

?>