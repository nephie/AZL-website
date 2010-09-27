<?php

class rteField extends formField {

	public function loadrte(){
		$response = responseLib::getInstance();


		$response->script("setTimeout(\"tinyMCE.execCommand('mceAddControl', false, '$this->id')\",500);");
	}
}

?>