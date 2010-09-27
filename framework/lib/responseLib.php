<?php

class responseLib {
	static $instance = null;

	public static function getInstance(){
	if(responseLib::$instance == null){
			responseLib::$instance = new highlightresponse();
		}

		return responseLib::$instance;
	}
}

?>