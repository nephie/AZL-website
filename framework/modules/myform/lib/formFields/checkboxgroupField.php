<?php

class checkboxgroupField extends formField {

	protected $boxes;

	public function checkboxgroupField($name , $label, $boxes,$extra = ''){
		$this->boxes = $boxes;
		foreach($boxes as $id => $box){
			if($box['selected']){
				$value[] = $id;
			}
		}
		parent::formField($name,$label,$value,$validator,$extra);
	}

}

?>