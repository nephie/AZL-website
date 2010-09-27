<?php

class myticketController extends controller {

	public function listusers($value){

		require(FRAMEWORK . DS . 'conf' . DS . 'mycafetaria.php');

		$model = new userModel();

		$adresult = $model->searchnamesforgroup('*' . $value . '*', $ordergroupdn);

		foreach ($adresult as $user){
				$result[$user->getName()] = $user->getName();
		}



		$ticketmodel = new myticketModel();

		$tickets = $ticketmodel->getfromContact('*' . $value . '*','',100);

		foreach ($tickets as $ticket){
			$result[$ticket->getContact()] = $ticket->getContact();
		}

		sort($result);

		return $result;
	}

	public function addTicket($parameters = array()){

		require(FRAMEWORK . DS . 'conf' . DS . 'myticket.php');
		require(FRAMEWORK . DS . 'conf' . DS . 'mycafetaria.php');

		$view = new ui($this);

		$form = new form($parameters);

		$currentuser = myauth::getCurrentuser();
		$currentname = '';
		$groups = $currentuser->getGroupid();

		if(!isset($groups[$algemenegebruikers])){
			$currentname = $currentuser->getName();
		}

		if(!isset($groups[$externgroup])){
			$pricefield = 'price';
		}
		else {
			$pricefield = 'price2';
		}

		$form->addField(new suggesttextField('myticket', 'listusers', 'contact', 'Uw naam',$currentname, array('required')));

		$dienstveld = new selectField('dienst', 'Dienst',array('required'));

		$groupmodel = new groupModel();
		$diensten = array();

		foreach($groups as $groupid){
			$group = $groupmodel->getfromId($groupid);
			if(count($group) == 1){
				$group = $group[0];

				$groupmemberof = $group->getMemberof();

				$flipped = array_flip($groupmemberof);

				if(isset($flipped[$meldinggroep])){
					$diensten[$group->getId()] = $group->getDescription();
				}
			}
		}

		if(count($diensten) > 1){
			$dienstveld->addOption(new selectoptionField('','',true));
			asort($diensten);
			foreach($diensten as $id => $desc){
				$dienstveld->addOption(new selectoptionField($desc,$id,false));
			}
		}
		else {
			foreach($diensten as $id => $desc){
				$dienstveld->addOption(new selectoptionField($desc,$id,true));
			}
		}

		$form->addField($dienstveld);

		$to = new selectField('to', 'Melding aan',array('required'));
		$to->addOption(new selectoptionField('',''));

		$meldingdienstenmodel = new meldingdienstenModel();

		$meldingdiensten = $meldingdienstenmodel->get();

		foreach($meldingdiensten as $meldingdienst){
			$to->addOption(new selectoptionField($meldingdienst->getName(),$meldingdienst->getId()));
		}

		$form->addField($to);

		$form->addField(new textField('titel','Titel','',array('required')));

		$form->addField(new textareaField('message','Melding','',array('required')));

		if($form->validate()){
			$ticket = new myticketObject();

			$ticket->setDienstid($form->getFieldvalue('dienst'));
			$ticket->setTo($form->getFieldvalue('to'));
			$ticket->setContact($form->getFieldvalue('contact'));
			$ticket->setTitel($form->getFieldvalue('titel'));
			$ticket->setMessage($form->getFieldvalue('message'));
			$ticket->setPlacedby($currentuser->getId());
			$ticket->setTime(time());
			$ticket->setStatus('Created');

			$ticket->setUser($currentuser->getName());

			$dienst = $groupmodel->getfromId($form->getFieldvalue('dienst'));
			$ticket->setDienst($dienst[0]->getDescription());

			$to = $meldingdienstenmodel->getfromId($form->getFieldvalue('to'));
			$ticket->setToname($to[0]->getName());

			$ticketmodel = new myticketModel();
			$saved = true;
			try{
				$ticketmodel->save($ticket);
			}
			catch(Exception $e){
				$saved = false;
			}

			$mailed = $this->mail($ticket);

			if($mailed){
				$ticket->setStatus('Mailed');
				$ticketmodel->save($ticket);
			}

			$this->listmytickets();

			$message = new ui($this);
			$message->assign('saved',$saved);
			$message->assign('mailed',$mailed);

			if($saved && $mailed){
				$type = 'success';
			}
			elseif ($saved) {
				$type = 'error';
			}
			elseif ($mailed){
				$type = 'warning';
			}
			else {
				$type = 'error';
			}

			$popupcontroller = new popupController();
			$popupcontroller->createflash(array('name' => 'flash_melding','type' => $type, 'content' => $message->fetch('myticket_saveflash.tpl')));


		}
		elseif(!$form->isSent()) {
			$view->assign('form',$form);
			$this->response->assign($this->self,'innerHTML',$view->fetch('myticket_addTicket.tpl'));
		}
	}

	public function closeShowticket($parameters = array()){
		$this->response->assign('ticketcontainer','innerHTML', '' );
	}

	public function listmytickets($parameters = array()){
		require(FRAMEWORK . DS . 'conf' . DS . 'myticket.php');
		$view = new ui($this);

		$currentuser = myauth::getCurrentuser();

		$groupmodel = new groupModel();
		foreach($currentuser->getGroupid() as $groupid){
			$group = $groupmodel->getfromId($groupid);
			if(count($group) == 1){
				$group = $group[0];

				$groupmemberof = $group->getMemberof();

				$flipped = array_flip($groupmemberof);

				if(isset($flipped[$meldinggroep])){
					$diensten[$group->getId()] = $group->getDescription();
				}
			}
		}

		asort($diensten);

		foreach($diensten as $id => $description){
			$tmp = new mygrid('mytickets_' . $id);
			$tmp->setModel(new myticketModel());
			$tmp->setDefaultpagesize(15);
			$cond = array('dienstid' => array('mode' => '=','value' => $id));
			$tmp->setDefaultconditions($cond);
			$tmp->setDefaultorder(array('fields' => array('time'), 'type' => 'DESC'));

			$tmp->registerRequest('titel' , 'myticket' , 'showticket' , array('id' => '{id}'));

			$tickets[$description] = $tmp;
		}

		$view->assign('tickets',$tickets);

		$this->response->assign($this->self,'innerHTML',$view->fetch('myticket_listmytickets.tpl'));
	}

	public function listTicketstome($parameters = array()){
		require(FRAMEWORK . DS . 'conf' . DS . 'myticket.php');
		$view = new ui($this);

		$mdienstenmodel = new meldingdienstenModel();

		$mdiensten = $mdienstenmodel->get();

		foreach($mdiensten as $dienst){
			if(myacl::isAllowed(myauth::getCurrentuser(),$dienst,'view')){
				$diensten[$dienst->getId()] = $dienst;
			}
		}


		asort($diensten);

		foreach($diensten as $id => $dienst){
			$tmp = new mygrid('myticketstome_' . $id);
			$tmp->setModel(new myticketModel());
			$tmp->setDefaultpagesize(15);
			$cond = array('to' => array('mode' => '=','value' => $dienst->getId()));
			$tmp->setDefaultconditions($cond);
			$tmp->setDefaultorder(array('fields' => array('time'), 'type' => 'DESC'));

			$tmp->registerRequest('titel' , 'myticket' , 'showticket' , array('id' => '{id}'));

			$tickets[$dienst->getName()] = $tmp;
		}

		$view->assign('tickets',$tickets);

		$this->response->assign($this->self,'innerHTML',$view->fetch('myticket_listmytickets.tpl'));
	}

	public function showTicket($parameters = array()){
		$view = new ui($this);

		$ticketModel = new myticketModel();

		$ticket = $ticketModel->getfromId($parameters['id']);

		if(count($ticket) == 1){
			$view->assign('ticket',$ticket[0]);
		}

		$closerequest = new ajaxrequest('myticket', 'closeShowticket');
		$view->assign('closerequest', $closerequest);

		$this->response->assign('ticketcontainer','innerHTML',$view->fetch('myticket_showticket.tpl'));
	}

	protected function mail($ticket){
		require(FRAMEWORK . DS . 'conf' . DS . 'myticket.php');

		$replyto = '';

		$usermodel = new userModel();
		$user = $usermodel->getfromDisplayname($ticket->getContact());
		if(count($user) == 1){
			$mail = $user[0]->getMail();
			if($mail != ''){
				$replyto = $mail;
			}
			else {
				$replyto = $defaultfrom;
			}
		}
		else {
			$replyto = $defaultfrom;
		}


		$dienstModel = new groupModel();

		$dienstname = '';
		$dienst = $dienstModel->getfromId($ticket->getDienstid());
		if(count($dienst) == 1){
			$dienstname = $dienst[0]->getDescription();
		}

		$to = '';
		$meldingdienstenModel = new meldingdienstenModel();

		$dienstto = $meldingdienstenModel->getfromId($ticket->getTo());
		if(count($dienstto) == 1){
			$to = $dienstto[0]->getMail();
			$toname = $dienstto[0]->getName();
		}
		else {
			throw new Exception('No mail address for recipiënt');
		}

		$subject = 'Melding [' . $ticket->getId() . '] aan [' . $toname . ']: ' . $ticket->getTitel();

		$message = '<h1>Melding ' . $toname . '</h1>';

		$message .= '
			<table>
				<tr>
					<td valign="top"><strong>Naam:</strong></td>
					<td>' . $ticket->getContact() . '</td>
				</tr>

				<tr>
					<td valign="top"><strong>Afdeling:</strong></td>
					<td>' . $dienstname . '</td>
				</tr>

				<tr>
					<td valign="top"><strong>Omschrijving:</strong></td>
					<td>' . nl2br($ticket->getMessage()) . '</td>
				</tr>
			</table>
		';

		return mail($to , $subject , $message , 'Content-Type: text/html; charset=UTF-8' . "\r\n" . 'From: ' . $replyto . "\r\n" . 'Reply-To: ' . $replyto, '-f ' . $replyto);
	}
}

?>