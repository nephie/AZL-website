<?php

class myauth {

	private static $currentuser;
	private static $currentpageid;

	public static function getLoginrequest(){
		require(FRAMEWORK . DS . 'conf' . DS . 'dispatcher.php');

		return new pagerequest($loginPageid);
	}

	public static function getLogoutrequest(){
		return new ajaxrequest('myauth' , 'logout' , array('userid' => self::$currentuser->getId()));
	}

	public static function getCurrentuser() {

		require(FRAMEWORK . DS . 'conf' . DS . 'auth.php');


		if(! (self::$currentuser instanceof userObject ) ){


			if(isset($_COOKIE['userid']) && isset($_COOKIE['challenge']) ){
				$loginModel = new loginModel();

				$useridCond['userid'] = array('mode' => '=' , 'value' => $_COOKIE['userid']);
				$challengeCond['challenge'] = array('mode' => '=' , 'value' => $_COOKIE['challenge']);
				$cond['AND'] = array($useridCond , $challengeCond);

				$logins = $loginModel->get($cond);
				//	This should give us 1 hit
				if(count($logins) == 1){
					$userid = $_COOKIE['userid'];
					$model = new userModel();
					$tmp = $model->getfromId($userid);
				}
				else {
					$userid = $defaultUserid;
					$model = new userModel(1);
					$tmp = $model->getfromId($userid);
				}

			}
			elseif($_SERVER['REMOTE_USER'] != '' && !isset($_COOKIE['noremoteuser'])){
				$cred = explode('\\',$_SERVER['REMOTE_USER']);
				if(count($cred) == 2){
					list($domainpart, $user) = $cred;
				}
				else{
					$user = $cred;
				}

				$model = new userModel();

				$tmp = $model->getfromUsername(addslashes($user));
				if(count($tmp) == 1){
					$userid = $tmp[0]->getId();
				}
				else {
					$userid = $defaultUserid;
					$tmp = $model->getfromId($userid);
				}
			}
			else {
				$userid = $defaultUserid;
				$model = new userModel(1);
				$tmp = $model->getfromId($userid);
			}



			if(count($tmp) != 1){
				throw new Exception('user could not be retrieved');
			}

			self::$currentuser = $tmp[0];
		}
		/*

		if(! (self::$currentuser instanceof userObject ) ){
			$user = new userObject();

			$user->setId('c5c1c65fd9d4144aafb6fe15abc7f366');
			$user->setName('Tim D\'Hooge - FAKE');
			$user->setUsername('tim.dhooge');
			$user->setDescription('');
			$user->setMail('');
			$user->setMemberof(array('CN=dienst_informatica,OU=Diensten,OU=Groepen,DC=stadskliniek,DC=lokeren,DC=be'));
			$user->setGroupid(array('799045fc4d2cee4090c2c5fb5a121942'));

			self::$currentuser = $user;
		}
*/
		$_SESSION['authenticated_user'] = self::$currentuser->getName();
		return self::$currentuser;
	}

	public static function setCurrentuser($user){

		$challenge = uniqid();
		$userid = $user->getId();


		$loginModel = new loginModel();

			$login = new loginObject();
			$login->setUserid($userid);

		$login->setChallenge($challenge);
		$login->setTime(time());

		//	Save it on the server
		$loginModel->save($login);

		//	And save it on the client
		setcookie('userid' , $userid);
		setcookie('challenge' , $challenge);


		// Clean out stale logins for this user (older than 24h)
		$idCond['id'] = array('mode' => '!=' , 'value' => $login->getId());
		$useridCond['userid'] = array('mode' => '=' , 'value' => $userid);
		$timeCond['time'] = array('mode' => '<','value' => time() - 60*60*24);

		$condition['AND'] = array($idCond , $useridCond, $timeCond);

		$loginModel->delete($condition);

		self::$currentuser = $user;
	}

	public static function getCurrentpageid(){
		if(self::$currentpageid == '' && isset($_SESSION['pageid'])) {
			self::setCurrentpageid($_SESSION['pageid']);
		}

		return self::$currentpageid;
	}

	public static function setCurrentpageid($pageid){
		self::$currentpageid = $pageid;
		$_SESSION['pageid'] = $pageid;
	}
}

?>