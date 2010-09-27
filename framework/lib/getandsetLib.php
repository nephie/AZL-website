<?php

class getandsetLib {

	private $reflectionClass;

	/**
	 * The magic method __call() allows to capture invocation of non existing methods.
	 * This method is used for the get, set, add and remove <i>attribute</i>
	 *
	 * __call wordt de �magische functie� genoemd. Wanneer een functie
	 * niet bestaat, wordt deze functie opgeroepen. Men gebruikt deze dus
	 * om de get en set van Object classes te automatiseren. Men moet de
	 * naamconventies respecteren. Indien een getter wordt opgeroepen
	 * met een boolean als parameter (TRUE), betekent dit dat hij de elementen
	 * niet moet gaan zoeken in de database maar enkel het element moet terugsturen.
	 *
	 * @param mixed $function is de opgeroepen methode die niet bestaat
	 * @param array $arguments de parameters gelinkt aan deze mehode
	 * @return unknown
	 * @throws callException, methodException
	 */
	function __call($method, $arguments)
	{
		$function = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $method));

	//  Split the function name into _2_ parts. The first part must be get|set|add|remove , the second the variablename
        list( $action , $variable) = explode('_' , $function , 2 );

        //  Make sure the variable exists
        $this->reflectionClass = new ReflectionClass($this);
        if( $this->reflectionClass->hasProperty($variable)){
            //  Is it a valid action?
            switch ($action) {
                case 'set': $this->_set($variable , $arguments[0]);
                    break;
                case 'is':
                case 'get': return $this->_get($variable);
                    break;
                case 'add': $this->_add($variable , $arguments[0]);
                    break;
                case 'remove': return $this->_remove($variable , $arguments[0]);
                    break;
                default: throw new methodException("Method $method does not exist\n");
            }
        }
        else {
            throw new methodException("Method $method does not exist\n");
        }
	}

  	/**
  	 * Set the variable with the value
     * @param string $variable
     * @param mixed $value
     */
    public function _set($variable, $value){
    	if($this->reflectionClass->hasMethod('set'.ucfirst($variable))){
    		// _set was called directly, not our favorite
    		$function = 'set'.ucfirst($variable);
    		return $this->$function($value);
    	}
    	else {
        	$this->$variable = $value;
    	}
    }

    /**
     * Return the variable
     * @param string $variable
     * @return mixed
     * @throws searchException, associationException
     */
    public function _get($variable){
    	if($this->reflectionClass->hasMethod('get'.ucfirst($variable))){
    		// _get was called directly, not our favorite
    		$function = 'get'.ucfirst($variable);
    		return $this->$function();
    	}
    	else {
    		return $this->$variable;
    	}
    }

    /**
     * add the variable
     * @param string $variable
     * @param mixed $value
     */
    public function _add($variable, $value){
        if($this->$variable == ''){
            $this->$variable = array();
        }
        else {
            if( ! is_array($this->$variable)){
                $tmp = $this->$variable;
                $this->$variable = array($tmp);
            }
        }

        if(is_array($value)){
        	$this->$variable = $this->$variable + $value;
        }
        else {
        	array_push($this->$variable, $value);
        }

    }

    /**
     * Remove the variable
     * @param string $variable
     * @param mixed $value
     */
    public function _remove($variable, $value){
        while(($index = array_search($value, $this->$variable)) !== false){
           array_splice($this->$variable, $index, 1);
        }
    }
}

?>