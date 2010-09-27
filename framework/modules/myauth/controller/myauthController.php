<?php

class myauthController extends controller {

	public function status($parameters = array()){
		require(FRAMEWORK . DS . 'conf' . DS . 'auth.php');

		$currentuser = myauth::getCurrentuser();

		$template = new ui($this);

		if($currentuser->getId() != $defaultUserid){

			$template->assign('logoutRequest' , myauth::getLogoutrequest());
			$template->assign('currentuser' , $currentuser);
			$this->response->assign($this->self , 'innerHTML' , $template->fetch($parameters['viewprefix'] . 'myauth_status_loggedin.tpl'));
			$this->title = 'Logged in';
		}
		else {
			$template->assign('loginRequest' , myauth::getLoginrequest());
			$this->response->assign($this->self , 'innerHTML', $template->fetch($parameters['viewprefix'] . 'myauth_status_notloggedin.tpl'));
		}
	}

	public function loginform($parameters = array()){

		require(FRAMEWORK . DS . 'conf' . DS . 'auth.php');
		require(FRAMEWORK . DS . 'conf' . DS . 'dispatcher.php');

		$currentuser = myauth::getCurrentuser();

		$groups = $currentuser->getGroupid();

		$groupfound = false;
		foreach($groups as $groupname => $groupid){
			if(isset($defaultPageids[$groupname])){
				$groupfound = true;
				$defaultPageid = $defaultPageids[$groupname];
			}
		}

		if(!$groupfound){
			$defaultPageid = $defaultPageids['default'];
		}

		if($currentuser->getId() == $defaultUserid){
			$form = new form($parameters);

			$username = new textField('username', 'Gebruiker' , '' , array('required') );
			$password = new passwordField('password' , 'Wachtwoord' , array('required'));

			$form->addField($username);
			$form->addField($password);

			$form->setSubmittext('Log in');
			$form->setResettext('Herbegin');

			$template = new ui($this);

			if($form->validate()){
				$usermodel = new userModel();
				$authuser = $usermodel->auth($parameters['username'] , $parameters['password']);

				if($authuser instanceof userObject ){
					myauth::setCurrentuser($authuser);
					//	Refresh the page
					$this->response->redirect('?pageid=' . $defaultPageid);
				}
				else {
					$this->response->assign('formerror_' . $form->getId() , 'innerHTML' , 'De ingevulde gegevens zijn niet correct.',true);
				}
			}
			elseif(!$form->isSent())  {
				$template->assign('form' , $form);

				$this->response->assign($this->self , 'innerHTML', $template->fetch($parameters['viewprefix'] . 'myauth_loginform.tpl'));
			}
			else {
				$this->response->assign('formerror_' . $form->getId() , 'innerHTML' , 'Alle velden moeten ingevuld worden.',true);
			}
		}
		else{
			$template = new ui($this);

			$defrequest = new pagerequest($defaultPageid);
			$template->assign('defrequest' , $defrequest);

			$template->assign('logoutRequest' , myauth::getLogoutrequest());
			$template->assign('currentuser' , $currentuser);
			$this->response->assign($this->self , 'innerHTML' , $template->fetch($parameters['viewprefix'] . 'myauth_loginform_loggedin.tpl'));
			//$this->response->assign($this->self , 'innerHTML' , '');
		}
	}

	public function logout($parameters = array()){
		$currentuser = myauth::getCurrentuser();

		if($currentuser->getId() == $parameters['userid']){
			//	The user himself wants to logout
			//	Get it of the server
			$loginModel = new loginModel();
			$loginModel->deletebyUserid($parameters['userid']);

			//	Get it of the client
			setcookie('userid' , '' , time() - (60 * 60 * 24 * 356));
			setcookie('challenge' , '' , time() - (60 * 60 * 24 * 356));

			//	No auto-login after a logout
			setcookie('noremoteuser' , 'TRUE');

			//	Refresh the page
			$this->response->redirect();
		}
		else {
			//	Someone wants to logout someone else, that's not allowed
			throw new securityException();
		}
	}
}

?>