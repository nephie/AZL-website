<?php

class selectField extends formField {

	protected $option;
	protected $optgroup;
	protected $multiple;

	protected $index = 0;

	public function selectField($name , $label, $validator = '' , $multiple = false, $extra = ''){
		parent::__construct($name , $label, '', $validator,$extra);

		$this->option = array();
		$this->optgroup = array();

		$this->multiple = $multiple;
	}

	public function addOption($option){
        if($this->option == ''){
            $this->option = array();
        }
        else {
            if( ! is_array($this->option)){
                $tmp = $this->option;
                $this->option = array($tmp);
            }
        }
        $this->option[$this->index++] = $option;
	}

	public function addOptgroup($optgroup){
        if($this->optgroup == ''){
            $this->optgroup = array();
        }
        else {
            if( ! is_array($this->optgroup)){
                $tmp = $this->optgroup;
                $this->optgroup = array($tmp);
            }
        }
        $this->optgroup[$this->index++] = $optgroup;
	}

	public function setValue($value){
		$this->value = $value;

		foreach ($this->option as $option){
			if(is_array($value)){
				if(in_array($option->getValue() , $value )){
					$option->setSelected(true);
				}
				else {
					$option->setSelected(false);
				}
			}
			else {
				if($option->getValue() == $value){
					$option->setSelected(true);
				}
				else {
					$option->setSelected(false);
				}
			}
		}

		foreach ($this->optgroup as $optgroup){
			foreach ($optgroup->getOption() as $option){
				if(is_array($value)){
					if(in_array($option->getValue() , $value )){
						$option->setSelected(true);
					}
					else {
						$option->setSelected(false);
					}
				}
				else {
					if($option->getValue() == $value){
						$option->setSelected(true);
					}
					else {
						$option->setSelected(false);
					}
				}
			}
		}
	}
}

?>