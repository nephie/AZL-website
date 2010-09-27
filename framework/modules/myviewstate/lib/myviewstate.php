<?php

class myviewstate {

	public static function set( $namespace , $name , $value) {
		$_SESSION[$namespace][$name] = serialize($value);
	}

	public static function get($namespace, $name) {
		return unserialize(serialize(unserialize($_SESSION[$namespace][$name])));
	}

	public static function getall($namespace) {
		$return = array();

		foreach($_SESSION[$namespace] as $key => $value){
			$return[$key] = myviewstate::get($namespace,$key);
		}

		return $return;
	}

	public static function rebuild($object,$namespace){
		$values = myviewstate::getall($namespace);

		if(count($values) > 0){
			foreach($values as $key => $value){
				$function = 'set' . ucfirst($key);

				$object->$function($value);
			}
			return true;
		}
		else {
			return false;
		}

	}
}

?>