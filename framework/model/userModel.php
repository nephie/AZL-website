<?php

require_once('aduserModel.php');
/*
class userModel extends mssqlmodel {

	protected $mapping = array('id' => 'id' , 'name' => 'name' , 'password' => 'password' , 'description' => 'description');

	protected $assoc = array(
							'groupid' => array(
											'type' => 'habtm',
											'joinmodel' => 'usergroupModel',
											'class' => 'usergroupObject',
											'foreignkey' => 'userid',
											'relationkey' => 'id',
											'assocforeignkey' => 'groupid',
											'assocrelationkey' => 'id',
											'condition' => array(),
										)
						);

	public function auth($username,$password){

		$usernameCond['name'] = array('mode' => '=' , 'value' => $username);
		$passwordCond['password'] = array('mode' => '=' , 'value' => md5($password));

		$condition['AND'] = array($usernameCond , $passwordCond);

		$users = $this->get($condition);

		if(count($users) == 1){
			return $users[0];
		}
		else {
			return false;
		}
	}
}
*/
?>