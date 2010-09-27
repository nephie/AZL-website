<?php

abstract class model {

	//	Configurable variables
	protected $datastore = 'default';
	protected $mapping = array();
	protected $id = 'id';
	protected $arrayFields = array();
	protected $cacheFields = array();
	protected $extrasearchfields = array();
	protected $datefields = array();
	protected $latesortfields = array();

	//	Assosiactions
	protected $assoc = array();


	//	Non-configurable variables
	protected static $connection = array();
	protected static $cache = array();
	protected $con;


	protected static $transactionStarted = array();


	abstract public function get($conditions = array() , $order = '', $amount=0 , $offset =0,$noassoc =false);

	abstract public function getmax($attr , $conditions = array() , $amount=0 , $offset =0,$noassoc = false);



	abstract public function getcount($conditions = array(),$noassoc =false);

	abstract public function delete($conditions = array());

	abstract public function parseString($string);

	abstract public function save($object);

	public function __construct(){
        require(FRAMEWORK . DS . 'conf' . DS . 'cacheFields.php');
        if(isset(${get_class($this)}))
        	$this->cacheFields = ${get_class($this)};
    }

    public function getIdattribute(){
    	return $this->id;
    }

	protected function filterConditions($conditions,$filter){

		$filter = array_flip($filter);

		$filteredconditions = '';

		foreach($conditions as $key => $condition){
			if(!isset($condition['value'])){
				$tmp = $this->filterConditions($condition,$filter);
				if(is_array($tmp)){
					$filteredconditions[$key] = $tmp;
				}
			}
			else {
				if(!isset($filter[$key])){
					$filteredconditions[$key] = $condition;
				}
			}
		}

		return $filteredconditions;
	}

    /**
     * This function will return a object filled with the data contained in the array given as parameter
     *
     *
     * @param array $data is een array van gegevens waarin de gegevens moeten worden getransformeerd van
     * de database naar data-attributen.
     * @return Object
     * @throws classException
     */
	protected  function fillObject($data,$noassoc = false){
		$class = str_replace('Model' , '' , get_class($this)) . 'Object';
		try{
			$object = new $class;
		}catch(classException $e){
			throw $e;
		}

		//foreach mapping datas
		foreach ($this->mapping as $dbattribute => $dataAttribute){
			$function = 'set' . ucfirst($dataAttribute);
			if(in_array($dataAttribute,$this->arrayFields)){
				if($data[$dbattribute] != ''){
					$test = unserialize($data[$dbattribute]);
					if(!$test){
						$data[$dbattribute] = unserialize(stripslashes($data[$dbattribute]));
					}
					else {
						$data[$dbattribute] = $test;
					}
				}
				else {
					$data[$dbattribute] = array();
				}
			}

			try{
				$object->$function($data[$dbattribute]);
			}catch(methodException $e){
				throw new classException($e->getMessage());
			}
		}

		//	Associations
		if(!$noassoc){
	        foreach ($this->assoc as $var => $assoc){
	        	$function = 'get' . ucfirst($assoc['relationkey']);

	        	$condition = array(
	        					$assoc['foreignkey'] => array(
	        												'mode' => '=',
	        												'value' => $object->$function()
	        											)
	        				);
	        	$extraCond = $assoc['condition'];

	        	if(is_array($extraCond) && count($extraCond) > 0){
	        		$cond = array('AND' => array($condition,$extraCond));
	        	}
	        	else {
	        		$cond = $condition;
	        	}
	        	$model = new $assoc['joinmodel']();
	        	$assocObject = $model->get($cond);

	        	foreach ($assocObject as $obj){
	        		if($assoc['type'] == 'map'){
	        			$setFunction = 'set' . ucfirst($var);
	        		}
	        		else {
	        			$setFunction = 'add' . ucfirst($var);
	        		}
	        		$getFunction = 'get' . ucfirst($assoc['assocforeignkey']);
	        		$object->$setFunction($obj->$getFunction());

	        		if(is_array($assoc['extrafields'])){
	        			foreach($assoc['extrafields'] as $key => $field){
	        				$object->_add($key,array($obj->_get($assoc['assocforeignkey']) => $obj->_get($field)));
	        			}
	        		}
	        	}
	        }
		}
		return $object;
	}

	/**
	 * The magic method __call() allows to capture invocation of non existing methods.
	 * This method is used for the getFrom and deleteBy <i>attribute</i>
	 * Deze functie wordt gebruikt om de functies getFrom en deleteBy te automatiseren.
	 * Dit is de reden waarom het opvolgen van de conventies qua syntax absoluut
	 * noodzakelijk zijn: getfrom  (kleinletter) en de eerste letter van het argument in hoofdletter.
	 * De functie get of delete worden opgeroepen met variabel $arguments
	 *
	 * @param mixed $function is de functie die werd ontvangen door de call doordat zij niet bestond
	 * @param array $arguments zijn de argumenten
	 * @return void
	 * @throws callException
	 *
	 */

	public function __call($function , $arguments){
        //  Split the function name into _2_ parts. The first part must be getfrom , the second the attributename
        list( $action , $variable) = explode('_' , strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $function)) , 2 );

        if ($action == 'getfrom')
        {
        //  There should only be one argument for anything in this class
	        if(count($arguments) > 4 ){
	        	throw new callException("Wrong number of arguments\n");
	        }

	        //  Make sure the variable exists
	        if(in_array($variable , $this->mapping , true) || isset($this->assoc[$variable])){

	            $condition = array( $variable => array('mode' => '=' , 'value' => $arguments[0]));
	            try{
	            	$return = $this->get($condition, $arguments[1],$arguments[2], $arguments[3]);
	            }catch (Exception $e){
	            	throw new callException($e->getMessage());
	            }
	        }
	        else {
	            throw new callException("Method $function does not exist\n");
	        }
        }
        else if ($action == 'deleteby')
        {
        	//  Make sure the variable exists
	        if(in_array($variable , $this->mapping , true) || isset($this->assoc[$variable])){
	            $condition = array( $variable => array('mode' => '=' , 'value' => $arguments[0]));
	            try{
	            	$return = $this->delete($condition);
	            }catch (Exception $e){
	            	throw new callException($e->getMessage());
	            }
	        }
	        else {
	            throw new callException("Method $function does not exist\n");
	        }
        }
        return $return;
    }

    public function getcolumns(){
    	$column = $this->mapping;

    	return $column;
    }

    public function getExtrasearchconds($search,$cond){
    	$extra = array();
    	if(count($this->extrasearchfields) > 0 || count($this->datefields) > 0){
			$baseset = $this->get($cond);
			foreach($baseset as $row){
				foreach($this->extrasearchfields as $extrafield){
					if(stripos($row->_get($extrafield),$search) !== false){
						$extra[] = array('id' => array('mode' => '=', 'value' => $row->getId()));
						break;
					}
				}
				foreach($this->datefields as $extrafield){
					if(stripos(date('H:i - d/m/Y',$row->_get($extrafield)),$search) !== false){
						$extra[] = array('id' => array('mode' => '=', 'value' => $row->getId()));
						break;
					}
				}
			}
    	}
    	return $extra;
    }

    public function getAssoc(){
    	return $this->assoc;
    }
}

?>