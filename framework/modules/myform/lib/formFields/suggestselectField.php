<?php

class suggestselectField extends formField {

	protected $callbackcontroller;
	protected $callbackfunction;

	protected $option;
	protected $optgroup;
	protected $multiple;

	protected $index = 0;

	protected $extraparams;

	protected $value2;



	public function suggestselectField($callbackcontroller, $callbackfunction , $name , $label, $defaultValue = '' , $validator = '', $extraparams = array(), $extra = ''){
		$this->callbackcontroller = $callbackcontroller;
		$this->callbackfunction = $callbackfunction;

		parent::__construct($name , $label, $defaultValue , $validator,$extra);

		$this->option = array();
		$this->optgroup = array();

		$this->multiple = true;

		$this->extraparams = $extraparams;
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