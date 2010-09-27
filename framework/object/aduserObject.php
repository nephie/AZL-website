<?php

class userObject extends object {

	protected $id;
	protected $name;
	protected $username;
	protected $description;
	protected $mail;
	protected $eid;

	protected $memberof = array();
	protected $groupid = array();


	public function getMyaclrelated(){
		$type = 'groupObject';

		foreach ($this->groupid as $groupid){
			$related[] = array('type' => $type, 'id' => $groupid);
		}

		return $related;
	}

	public function getMyacldisplayfield(){
		return $this->name;
	}
}
?>