<?php

class selectoptionField extends getandsetLib {

	protected $name;
	protected $value;
	protected $selected;
	protected $extra;

	public function selectoptionField($name,$value,$selected = false,$extra = ''){
		$this->name = $name;
		$this->value = $value;
		$this->selected = $selected;
		$this->extra = $extra;
	}
}

?>