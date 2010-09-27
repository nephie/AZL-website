<?php

class viewstategetandsetLib extends getandsetLib {

	protected $namespace;

	public function _set($variable, $value){
        parent::_set($variable,$value);
        myviewstate::set($this->namespace,$variable,$this->$variable);
    }

 	public function _add($variable, $value){
        parent::_add($variable,$value);
		myviewstate::set($this->namespace,$variable,$this->$variable);
    }

    /**
     * Remove the variable
     * @param string $variable
     * @param mixed $value
     */
    public function _remove($variable, $value){
        parent::_remove($variable,$value);
        myviewstate::set($this->namespace,$variable,$this->$variable);
    }
}

?>