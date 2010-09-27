<?php

class groupObject extends object {

	protected $id;
	protected $name;
	protected $description;
	protected $mail;
	protected $displayname;

	protected $member = array();
	protected $memberof = array();

	protected $groupid = array();

	public function getMyacldisplayfield(){
		return $this->name;
	}
}

?>