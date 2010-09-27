<?php

class myvoorinschrijvingController extends controller {
	
	public function index($parameters){
		
		if($parameters['dag'] == 'zondag'){
			$userModel = new userModel();
			$user = $userModel->getfromId(3);
			myauth::setCurrentuser($user[0]);
		}
		
		//	Enkel indien ingelogd
		$target = new securitytarget();
		$target->setId('voorinschrijving_index');
		
		if(myacl::isAllowed(myauth::getCurrentuser(), $target, 'view')){
			
			$template = new ui($this);
			
			$starget = new securitytarget();
			$starget->setId('voorinschrijving_lijst');
			if(myacl::isAllowed(myauth::getCurrentuser() , $starget , 'view')){
				$lijst = new pagerequest(3);
				$template->assign('lijst',$lijst);
			}
			
			$secTarget = new securitytarget();
			$secTarget->setId('voorinschrijving_usertype');
//			if(!myacl::isAllowed(myauth::getCurrentuser(),$secTarget,'choose') && unserialize(serialize($_SESSION['gast'])) instanceof ingeschrevenObject ){
//				$gast = unserialize(serialize($_SESSION['gast']));
//				$template->assign('gast' , $gast);
//				
//				$uurModel = new uurModel();
//				$uur = $uurModel->getfromId($gast->getUurid());
//				$uur = $uur[0];
//				$template->assign('uur', $uur);				
//				$this->response->assign($this->self,'innerHTML',$template->fetch('myvoorinschrijving_success.tpl'));
//				return;
//			}
			
			$secTarget = new securitytarget();
			$secTarget->setId('voorinschrijving_usertype');
			
			if(myacl::isAllowed(myauth::getCurrentuser(),$secTarget,'choose')){
				$form = new form($parameters);
				
				$userModel = new userModel();
				$users = $userModel->get();
				
				$types = new selectField('user','Type',array('required'));
				$types->addOption( new selectoptionField('','',true));
				foreach($users as $user){
					if(myacl::isAllowed($user,$secTarget,'get_chosen')){
						$types->addOption(new selectoptionField($user->getDescription(),$user->getId()));
					}
				}
				
				$form->addField($types);
				$template->assign('form' , $form);
				
				if($form->validate()){
					$chosenUser = $userModel->getfromId($form->getFieldvalue('user'));
					$chosenUser = $chosenUser[0];
				}
				else{
					$chosenUser = myauth::getCurrentuser();
				}
			}
			else {
				$chosenUser = myauth::getCurrentuser();
			}
			
			//	uren ophalen en filteren + sorteren per traject
			$uurModel = new uurModel();
			$uren = $uurModel->get();
			
			$trajecten = array();
			foreach($uren as $uur){
				if (myacl::isAllowed($chosenUser,$uur,'signup')){
					$trajecten[$uur->getTrajectid()][] = $uur;
				}
			}
			
			//	nog eens door alles heenlopen en alles structureren voor de template
			$mogelijkheden = array();
			$trajectModel = new trajectModel();
			$ingeschrevenModel = new ingeschrevenModel();
			foreach($trajecten as $trajectid => $uren){
				$temp = array();
				$traject = $trajectModel->getfromId($trajectid);
				$temp['traject'] = $traject[0];
				
				foreach($uren as $uur){
					$uurtemp = array();
					$total = 0;
					
					$uurtemp['uur'] = $uur;
					
					$gasten = $ingeschrevenModel->getfromUurid($uur->getId());
					foreach($gasten as $gast){
						$total += $gast->getAantal();
					}
					$uurtemp['vrij'] = $uur->getMaxaantal() - $total;
					
					$uurtemp['request'] = new ajaxrequest('myvoorinschrijving' , 'signup' , array('uurid' => $uur->getId(), 'userid' => $chosenUser->getId()));
					
					if($uurtemp['vrij'] > 0){
						$temp['uren'][] = $uurtemp;
					}
				}
				
				if(count($temp['uren']) > 0){
					$mogelijkheden[] = $temp;
				}
			}
			
			if(count($mogelijkheden) > 0){
				$template->assign('mogelijkheden' , $mogelijkheden);
				
				if($parameters['dag'] == 'zondag'){
					$this->response->assign($this->self , 'innerHTML' , $template->fetch('myvoorinschrijving_index.tpl'));
				}
				else {
					$this->response->assign($this->self , 'innerHTML' , $template->fetch('myvoorinschrijving_index2.tpl'));
				}
				
			}
			else
			{
				$this->response->assign($this->self , 'innerHTML' , $template->fetch('myvoorinschrijving_volzet.tpl'));
			}
		}
	}

	public function signup($parameters){
		
		
			
		$uurid = $parameters['uurid'];
		
		$terug = new pagerequest((isset($_GET['pageid'])? $_GET['pageid'] : 1));
		
		$uurModel = new uurModel;
		$uur = $uurModel->getfromId($uurid);
		
		if(count($uur) == 1){
			$uur = $uur[0];
		}
		else {
			$this->response->redirect();
		}
		
		$userModel = new userModel();
		$user = $userModel->getfromId($parameters['userid']);
		$user = $user[0];
		
		$trajectModel = new trajectModel();
		$traject = $trajectModel->getfromId($uur->getTrajectid());
		$traject = $traject[0];
		
		if(myacl::isAllowed($user, $uur, 'signup')){
			$template = new ui($this);
			
		$starget = new securitytarget();
		$starget->setId('voorinschrijving_lijst');
		if(myacl::isAllowed(myauth::getCurrentuser() , $starget , 'view')){
			$lijst = new pagerequest(3);
			$template->assign('lijst',$lijst);
		}
			
			$gastModel = new ingeschrevenModel();
			
			$vrij = $uur->getMaxaantal();
			$gasten = $gastModel->getfromUurid($uur->getId());
			foreach($gasten as $gast){
				$vrij -= $gast->getAantal();
			}

			$form = new form($parameters);
			
			$form->addField( new fixedField('traject' , 'Traject' , $traject->getName() ) );
			$form->addField( new fixedField('uur' , 'Uur' , $uur->getUur() ) );
			$form->addField( new textField('voornaam' , 'Voornaam' , '' , array('required')));
			$form->addField( new textField('achternaam' , 'Achternaam' , '' , array('required')));
			$form->addField( new textField('woonplaats' , 'Woonplaats' , '' , array('required')));
			$form->addField( new textField('mailaddress' , 'E-mail (optioneel)' , '' ));
						
			$aantal = new selectField('aantal' , 'Aantal' ,array('required'));
			
			for($i = 1; $i <= $vrij; $i++){
				$aantal->addOption( new selectoptionField($i,$i) );
			}
			
			$form->addField($aantal);
			
			
			$form->addField( new hiddenField('uurid' , $uurid));
			$form->addField( new hiddenField('userid' , $parameters['userid']));
			
			$form->setSubmittext('Inschrijven');
			$form->setResettext('Herbegin');
			
			if($form->validate()){
				$gast = new ingeschrevenObject();
				
				
				$gast->setVoornaam($form->getFieldvalue('voornaam'));
				$gast->setAchternaam($form->getFieldvalue('achternaam'));
				$gast->setWoonplaats($form->getFieldvalue('woonplaats'));
				$gast->setMailaddress($form->getFieldvalue('mailaddress'));
				$gast->setAantal($form->getFieldvalue('aantal'));
				$gast->setUurid($form->getFieldvalue('uurid'));
				
				$gast->setRegistrationtime(time());
				
				$secTarget = new securitytarget();
				$secTarget->setId('voorinschrijving_usertype');
				if(myacl::isAllowed(myauth::getCurrentuser(),$secTarget,'choose')){
					$gast->setUserid($parameters['userid']);
					
					$template->assign('terug' , $terug);
				} else {
					$gast->setUserid(myauth::getCurrentuser()->getId());
				}				
				
				if (!empty($_SERVER['HTTP_CLIENT_IP']))
				  //check ip from share internet
				  {
				    $ip=$_SERVER['HTTP_CLIENT_IP'];
				  }
				  elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
				  //to check ip is pass from proxy
				  {
				    $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
				  }
				  else
				  {
				    $ip=$_SERVER['REMOTE_ADDR'];
				  }
				$gast->setIpaddress($ip);
				
				$gastModel->save($gast);
				
				$vrij = $uur->getMaxaantal();
				$gasten = $gastModel->getfromUurid($uur->getId());
				foreach($gasten as $gast){
					$vrij -= $gast->getAantal();
				}
				
				if($vrij < 0){					
					$gastModel->deletebyId($gast->getId());
					$template->assign('terug' , $terug);
					$this->response->assign($this->self , 'innerHTML' , $template->fetch('myvoorinschrijving_error.tpl'));
				}
				else{
					$secTarget = new securitytarget();
					$secTarget->setId('voorinschrijving_usertype');
//					if(!myacl::isAllowed(myauth::getCurrentuser(),$secTarget,'choose')){
//						$_SESSION['gast'] = $gast;
//					}
//					else {						
//						$template->assign('terug' , $terug);
//					}
					
					$template->assign('gast' , $gast);
					$template->assign('uur' , $uur);					
					$template->assign('traject' , $traject);
					
					if($uur->getDag() == 2){
						$this->response->assign($this->self , 'innerHTML' , $template->fetch('myvoorinschrijving_success.tpl'));
					}
					else {
						$this->response->assign($this->self , 'innerHTML' , $template->fetch('myvoorinschrijving_success2.tpl'));
					}
					
					if($gast->getMailaddress() != ''){
						$mail['subject'] = 'Bevestiging Voorinschrijving Open bedrijvendag: AZ Lokeren.';
					    $mail['from'] = 'informatica@azlokeren.be';
					    $mail['Reply-To'] = $mail['from'];
					    
					    $mail['message'] = '
Voorinschrijving Open bedrijvendag: AZ Lokeren

U bent ingeschreven met de volgende gegevens:

Traject: ' . $traject->getName() . '
Uur: ' . $uur->getUur() . '
Voornaam: ' . $gast->getVoornaam() . '
Achternaam: ' . $gast->getAchternaam() . '
Woonplaats: ' . $gast->getWoonplaats() . '
E-Mail: ' . $gast->getMailaddress() . '
Aantal: ' . $gast->getAantal() . '

Gelieve deze gegevens bij de hand te hebben als u zich aanmeld.
';
					    if($uur->getDag() == 2){
					    	$mail['message'] .= '
					    	
Om het normale verkeer naar het ziekenhuis (ziekenwagens, artsen, personeel en bezoekers) in goede banen te leiden hebben we voor die dag de parking IDM, Zelebaan 42, Lokeren exclusief gereserveerd voor bezoekers aan de OBD.  
Een pendelbusverbinding naar het ziekenhuis is voorzien.

Voorzie best een korte tijdsmarge tussen aankomst op de parking en de start van het gekozen traject.
					    ';
					    }
					    mail($gast->getMailaddress() , $mail['subject'] , $mail['message'] , 'From: ' . $mail['from'] . "\r\n" . 'Reply-To: ' . $mail['Reply-To'], '-f ' . $mail['from']);
					}
				}
			}
			elseif(!$form->isSent())  {
				$template->assign('form' , $form);
				$template->assign('terug' , $terug);
	
				$this->response->assign($this->self , 'innerHTML', $template->fetch('myvoorinschrijving_form.tpl'));
			}
		}
	}

	public function lijst($parameters){
			$starget = new securitytarget();
			$starget->setId('voorinschrijving_lijst');
			if(!myacl::isAllowed(myauth::getCurrentuser() , $starget , 'view')){
				return;
			}
		$template = new ui($this);
		
		$uurModel = new uurModel();
		$ingeschrevenModel = new ingeschrevenModel();
		$trajectModel = new trajectModel();
		$userModel = new userModel();
		
		$form = new form($parameters);
		
		$select = new selectField('wat', '' , '');
		
		$select->addOption(new selectoptionField('Alles' , '9990'));
		
		$daggroup = new selectoptgroupField('Dagen');
		$daggroup->addOption(new selectoptionField('Zaterdag' , '6661'));
		$daggroup->addOption(new selectoptionField('Zondag' , '4442'));
		$select->addOptgroup($daggroup);
		
		$trajecten = $trajectModel->get();
		$trajectgroup = new selectoptgroupField('Trajecten');
		foreach($trajecten as $tr){
			$trajectgroup->addOption(new selectoptionField($tr->getName(), 888 . $tr->getId()));
		}
		$select->addOptgroup($trajectgroup);
		
		$typegroup = new selectoptgroupField('Type');
		$typegroup->addOption(new selectoptionField('Personeel' , '1114'));
		$typegroup->addOption(new selectoptionField('VIP' , '1115'));
		$select->addOptgroup($typegroup);
		
		$uren = $uurModel->get();
		$uurgroup = new selectoptgroupField('Uren - Zaterdag');
		$uurgroup2 = new selectoptgroupField('Uren - Zondag');
		foreach($uren as $uur){
			if($uur->getDag() == 1){
				$uurgroup->addOption(new selectoptionField($uur->getUur(), 777 . $uur->getId()));
			}
			else {
				$uurgroup2->addOption(new selectoptionField($uur->getUur(), 555 . $uur->getId()));
			}
		}
		$select->addOptgroup($uurgroup);
		$select->addOptgroup($uurgroup2);
		
		$form->addField($select);
		$template->assign('form' , $form);
		
		$terug = new pagerequest(1);
		$template->assign('terug' , $terug);
		
		if(!$form->isSent()){
			$wat = 9990;
		}
		else {
			$wat = $form->getFieldvalue('wat');
		}
		
		$mode = substr($wat,0,3);
		$modevalue = substr($wat,3);
		
		if($mode == 999 || $mode == 888){
			$dag = 0;
		}
		elseif ($mode == 777 || $mode == 666) {
			$dag = 1;
		}
		elseif($mode == 555 || $mode == 444) {
			$dag = 2;
		}
		
		if( $dag == 0 ){
			$lijst[0]['dag'] = 'Zaterdag';
			$lijst[1]['dag'] = 'Zondag';
		}
		elseif ($dag == 1){
			$lijst[0]['dag'] = 'Zaterdag';
		}elseif ($dag == 2){
			$lijst[1]['dag'] = 'Zondag';
		}
		
		
		
		foreach($lijst as $dag => $lijstelement){
			$dag++;
			
			if($mode == 888){
				$uurCond = array( 'AND' => array (
								'dag' => array(
										'mode' => '=',
										'value' => $dag
									),
								'trajectid' => array(
										'mode' => '=',
										'value' => $modevalue
									)
								)
					);
			}
			elseif( $mode == 777 || $mode == 555){
				$uurCond = array( 'AND' => array (
								'id' => array(
										'mode' => '=',
										'value' => $modevalue
									)
								)
					);
			}
			else {
				$uurCond = array( 'AND' => array (
									'dag' => array(
											'mode' => '=',
											'value' => $dag
										)
									)
						);
			}
			
			$uren = $uurModel->get($uurCond);
		
			foreach($uren as $uur){
				$tempuur = array();
				$tempuur['uur'] = $uur;
				
				$gasten = $ingeschrevenModel->getfromUurid($uur->getId());
				
				$tempuur['aantal'] = 0;
				foreach($gasten as $gast){
					$tempgast = array();
					$tempgast['gast'] = $gast;
					
					if($mode == 111){
						if($gast->getUserid() != $modevalue){
							continue;
						}
					}
					
					if($gast->getRegistrationtime() > time() - (60 * 60 * 24)){
						$tempgast['new'] = 'true';
					}
					else {
						$tempgast['new'] = 'false';
					}
				
					
					$sameip = $ingeschrevenModel->getfromIpaddress($gast->getIpaddress());
					if(count($sameip) > 1){
						$tempgast['sameip']['aantal'] = count($sameip);
						$tempgast['sameip']['wie'] = $sameip;
						
						$showrequest = new ajaxrequest('myvoorinschrijving' , 'showsameip' , array('id' => $gast->getId()));
						$hiderequest = new ajaxrequest('myvoorinschrijving' , 'hidesameip' , array('id' => $gast->getId()));
						
						$tempgast['sameip']['showrequest'] = $showrequest;
						$tempgast['sameip']['hiderequest'] = $hiderequest;
						
						
						
					}
					else {
						$tempgast['sameip'] = 0;
					}
					
					$showmorerequest = new ajaxrequest('myvoorinschrijving' , 'showmore' , array('id' => $gast->getId()));
						$hidemorerequest = new ajaxrequest('myvoorinschrijving' , 'hidemore' , array('id' => $gast->getId()));
						
						$tempgast['showmorerequest'] = $showmorerequest;
						$tempgast['hidemorerequest'] = $hidemorerequest;
					
					$deleterequest = new ajaxrequest('myvoorinschrijving' , 'deletegast' , array('gastid' => $gast->getId()));
					
					$tempgast['deleterequest'] = $deleterequest;
					
					$user = $userModel->getfromId($gast->getUserid());
					$tempgast['user'] = $user[0];
					
					$tempuur['aantal'] += $gast->getAantal();
					$tempuur['gasten'][] = $tempgast;
				}
				
				if(count($tempuur['gasten']) > 0){
				
					if(!isset($lijst[$dag - 1]['trajecten'][$uur->getTrajectid()]['traject'])){
						$traject = $trajectModel->getfromId($uur->getTrajectid());
						$lijst[$dag - 1]['trajecten'][$uur->getTrajectid()]['traject'] = $traject[0];
					}
					
					$lijst[$dag - 1]['trajecten'][$uur->getTrajectid()]['uren'][] = $tempuur;
				}
			}
		}
		
		foreach($lijst as $id => $dag){
			if(count($dag['trajecten']) == 0){
				unset($lijst[$id]);
			}
		}
		
		$template->assign('lijst' , $lijst);
		
		$this->response->assign($this->self, 'innerHTML' , $template->fetch('myvoorinschrijving_lijst.tpl'));
	}
	
	public function showsameip($parameters){
		$this->response->assign($parameters['id'] . '_same', 'style.display' , 'block');
		$template = new ui($this);
		$hiderequest = new ajaxrequest('myvoorinschrijving' , 'hidesameip' , array('id' => $parameters['id']));
		
		$template->assign('linktext' , 'Verberg');
		$template->assign('request' , $hiderequest);
		
		$this->response->assign($parameters['id'] . '_samelink', 'innerHTML' , $template->fetch('myvoorinschrijving_changelink.tpl'));
	}

	public function hidesameip($parameters){
		$this->response->assign($parameters['id'] . '_same', 'style.display' , 'none');
		$template = new ui($this);
		$showrequest = new ajaxrequest('myvoorinschrijving' , 'showsameip' , array('id' => $parameters['id']));
		
		$template->assign('linktext' , 'Toon');
		$template->assign('request' , $showrequest);
		
		$this->response->assign($parameters['id'] . '_samelink', 'innerHTML' , $template->fetch('myvoorinschrijving_changelink.tpl'));
	}
	
	public function showmore($parameters){
		$this->response->assign($parameters['id'] . '_more', 'style.display' , 'block');
		$template = new ui($this);
		$hidemorerequest = new ajaxrequest('myvoorinschrijving' , 'hidemore' , array('id' => $parameters['id']));
		
		$template->assign('linktext' , 'Minder informatie');
		$template->assign('request' , $hidemorerequest);
		
		$this->response->assign($parameters['id'] . '_morelink', 'innerHTML' , $template->fetch('myvoorinschrijving_changelink.tpl'));
	}

	public function hidemore($parameters){
		$this->response->assign($parameters['id'] . '_more', 'style.display' , 'none');
		
		$template = new ui($this);
		$showmorerequest = new ajaxrequest('myvoorinschrijving' , 'showmore' , array('id' => $parameters['id']));
		
		$template->assign('linktext' , 'Meer informatie');
		$template->assign('request' , $showmorerequest);
		
		$this->response->assign($parameters['id'] . '_morelink', 'innerHTML' , $template->fetch('myvoorinschrijving_changelink.tpl'));
	}
	
	public function deletegast($parameters){
		$gastid = $parameters['gastid'];
		
		$popupcontroller = new popupController();
		$gastModel = new ingeschrevenModel();
		$gast = $gastModel->getfromId($gastid);
		if(count($gast) == 1){
			$gast = $gast[0];
		}
		else {
			return;
		}
		
		$sure = $parameters['sure'];
		
		if($sure != 'sure'){
			$template = new ui($this);
			
			$template->assign('gast' , $gast);
			
			$ja = new ajaxrequest('myvoorinschrijving', 'deletegast' , array('gastid' => $gastid, 'sure' => 'sure'));
			$template->assign('ja' , $ja);
			
			$popupcontroller->create(array('name' => 'confirm' , 'content' => $template->fetch('myvoorinschrijving_confirmdelete.tpl')));
		}
		elseif(myacl::isAllowed(myauth::getCurrentuser(),$gast, 'delete')){
			$popupcontroller->destroy(array('name' => 'confirm'));
			
			$gastModel->deletebyId($gastid);
			$this->response->remove($gastid . '_rij');
		}
	}
}

?>
