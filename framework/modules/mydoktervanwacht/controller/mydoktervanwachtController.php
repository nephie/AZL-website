<?php

class mydoktervanwachtController extends controller {
	
	public function removedwaypoint($key, $action,$parameters,$currenthaspoints){

		if($action == 'showspecdetails'){
			$this->showoverzicht($parameters);
		}
		elseif($action == 'adddoktervanwacht'){
			$this->showspecdetails($parameters);
		}
	}
	
	public function showoverzicht($parameters = array()){
		$view = new ui($this);
		
		$specmodel = new wdokterspecialismeModel();
		$wachtmodel = new wachtdokterviewModel();
		
		$specs = $specmodel->get(array(),array('fields' => array('name'), 'type' => 'ASC'));
		
		$list = array();
		
		$startcond = array('start' => array('mode' => '<', 'value' => time()));
		$stopcond = array('stop' => array('mode' => '>', 'value' => time()));
		
		foreach ($specs as $spec){
			
			$temp = array();
			
			
				$temp['name'] = $spec->getName();
				$temp['request'] = new ajaxrequest('mydoktervanwacht', 'showspecdetails', array('specid' => $spec->getId()));
				
				$speccond = array('specialisme' => array('mode' => '=', 'value' => $spec->getId()));			
				$wachtdokter = $wachtmodel->get(array('AND' => array($startcond,$stopcond,$speccond)), array('type' => 'DESC', 'fields' => array('start')));
				
				if(count($wachtdokter) > 0){	
					$wachtdokter = $wachtdokter[0];
					$temp['wachtdokter'] = $wachtdokter;
				}
				
				$list[] = $temp; 
			
		}
		
		$view->assign('list',$list);
		
		$poolgrid = new mygrid('wachtpools');
		$poolgrid->setModel(new wdokterspecialismeModel());
		
		$view->assign('poolgrid',$poolgrid);
		
		$this->response->assign($this->self, 'innerHTML' , $view->fetch('mydoktervanwacht_overzicht.tpl'));
		
	
	}

	public function showspecdetails($parameters = array()){
		$view = new ui($this);
		
		$specmodel = new wdokterspecialismeModel();
		
		$spec = $specmodel->getfromId($parameters['specid']);
		
		if(count($spec) == 1){
			$spec = $spec[0];
			$view->assign('specialisme',$spec);
			
			$closerequest = new ajaxrequest('mydoktervanwacht', 'showoverzicht', array());
			$view->assign('closerequest',$closerequest);
			
			if($parameters['history'] != 'history'){
				$this->response->addWaypoint( 'mydoktervanwacht', 'showspecdetails', uniqid(), array('specid' => $parameters['specid'], 'startofmonth' => $parameters['startofmonth'], 'endofmonth' => $parameters['endofmonth']));
			}
			
			$output = array();
			
			
			$startofmonth = (isset($parameters['startofmonth']))? $parameters['startofmonth'] : mktime(00, 00, 00, date('m'), 01, date('Y'));
			$endofmonth = (isset($parameters['endofmonth']))? $parameters['endofmonth'] : mktime(00, 00, 00, date('m') + 1 , 01, date('Y')) - 1;
			
			$wachtdokterModel = new wachtdokterviewModel();
			
			$speccond = array('specialisme' => array('mode' => '=','value' => $parameters['specid']));
			for($i = $startofmonth; $i < $endofmonth; $i += 86400){				
					
				$start = array('start' => array('mode' => 'BETWEEN', 'value' => $i+1, 'topvalue' => ($i + 86400 -1)));
				$stop = array('stop' => array('mode' => 'BETWEEN', 'value' => $i, 'topvalue' => ($i + 86400 - 1)));
				$big = array('AND' => array(
									array('start' => array('mode' => '<','value' => $i+1)),
									array('stop' => array('mode' => '>','value' => $i + 86400 - 1))
								));
				$cond = array('OR' => array($start,$stop,$big));
				
				$dokters = $wachtdokterModel->get(array('AND' => array($speccond,$cond)),array('fields' => array('start'),'type'=> 'ASC'));
				
				$outputdokters = array();
				foreach($dokters as $dokter){
					$tmpdok = array();
					$tmpdok['dokter'] = $dokter;
					if(myacl::isAllowed(myauth::getCurrentuser(), $spec, 'managewacht')){
						if($dokter->getStart() > $i -1){
							$tmpdok['request'] = new ajaxrequest('mydoktervanwacht', 'deletedoktervanwacht', array('id' => $dokter->getId(),'specid' => $parameters['specid'], 'startofmonth' => $startofmonth, 'endofmonth' => $endofmonth));
						}
					}
					
					$outputdokters[] = $tmpdok;
				}
				
				$tmp = array();
				$tmp['dokters'] = $outputdokters;
				$tmp['start'] = $i;
				
				if(myacl::isAllowed(myauth::getCurrentuser(), $spec, 'managewacht')){
					$tmp['addrequest'] = new ajaxrequest('mydoktervanwacht', 'adddoktervanwacht', array('specid' => $parameters['specid'], 'startofmonth' => $startofmonth, 'endofmonth' => $endofmonth, 'start' => $tmp['start']));
				}
				
				$output[] = $tmp;
			}
			
			$view->assign('list',$output);
			
			$prevmonth = (date('m',$startofmonth) - 1 < 1) ? 12 : date('m',$startofmonth) - 1;
			$prevyear =  (date('m',$startofmonth) - 1 < 1) ? date('Y',$startofmonth) - 1 : date('Y',$startofmonth);
			$startofprevmonth = mktime(00, 00, 00, $prevmonth, 01, $prevyear);
			$endofprevmonth = $startofmonth - 1;
			$prevrequest = new ajaxrequest('mydoktervanwacht', 'showspecdetails', array('specid' => $parameters['specid'], 'startofmonth' => $startofprevmonth, 'endofmonth' => $endofprevmonth));
			$view->assign('prevrequest',$prevrequest);
			
			$nextmonth = (date('m',$startofmonth) + 1 > 12) ? 1 : date('m',$startofmonth) + 1;
			$nextyear =  (date('m',$startofmonth) + 1 > 12) ? date('Y',$startofmonth) + 1 : date('Y',$startofmonth);
			$startofnextmonth = $endofmonth + 1;
			$endofnextmonth = mktime(00, 00, 00, $nextmonth + 1, 01, $nextyear) -1;
			$nextrequest = new ajaxrequest('mydoktervanwacht', 'showspecdetails', array('specid' => $parameters['specid'], 'startofmonth' => $startofnextmonth, 'endofmonth' => $endofnextmonth));
			$view->assign('nextrequest',$nextrequest);

			
			
			$this->response->assign($this->self, 'innerHTML', $view->fetch('mydoktervanwacht_specdetails.tpl'));
			
			if(myacl::isAllowed(myauth::getCurrentuser(), $spec, 'managerights')){
				$aclcontroller = new myaclController();
				$aclcontroller->listacl(array('targetoutput' => 'acllist_wachtdokter_' . $parameters['specid'], 'objecttype' => 'wdokterspecialismeObject','objectid'=> $parameters['specid']));
			}
		}
	}
	
	public function adddoktervanwacht($parameters = array()){
		$view = new ui($this);
		
		if($parameters['history'] != 'history'){
			$this->response->addWaypoint( 'mydoktervanwacht', 'adddoktervanwacht', 'adddoktervanwacht' , array('specid' => $parameters['specid'], 'startofmonth' => $parameters['startofmonth'], 'endofmonth' => $parameters['endofmonth']));
		}

		$form = new form($parameters);

		$form->addField(new hiddenField('specid', $parameters['specid']));
				
		$select = new selectField('dokter', 'Dokter');
		
		$wdokterModel = new wdokterModel();
		$specmodel = new wdokterspecialismeModel();
		
		$maindocs = $wdokterModel->getfromSpecialisme($parameters['specid']);
		$mainspec = $specmodel->getfromId($parameters['specid']);
		
		$mainoptgroup = new selectoptgroupField($mainspec[0]->getName());
		
		foreach($maindocs as $maindok){
			$mainoptgroup->addOption(new selectoptionField('Dr. ' . $maindok->getNaam() . ' ' . $maindok->getVoornaam(), $maindok->getId()));			
		}		
		$select->addOptgroup($mainoptgroup);
		
		$specs = $specmodel->get(array(),array('fields' => array('name'),'type' => 'ASC'));
		
		foreach($specs as $spec){
			if($spec->getId() != $parameters['specid']){
				$tmp = new selectoptgroupField($spec->getName());
				
				$tmpdocs = $wdokterModel->getfromSpecialisme($spec->getId());
				
				foreach($tmpdocs as $tmpdok){
					$tmp->addOption(new selectoptionField('Dr. ' . $tmpdok->getNaam() . ' ' . $tmpdok->getVoornaam(), $tmpdok->getId()));
				}
				
				$select->addOptgroup($tmp);
			}
		}
		
		$form->addField($select);
		$form->addField(new datepickerField('start','Start',true,mktime(0,0,0,date('m',$parameters['start']),date('d',$parameters['start']),date('Y',$parameters['start'])),array('required')));
		$form->addField(new datepickerField('stop','Stop',true,mktime(23,59,0,date('m',$parameters['start']),date('d',$parameters['start']),date('Y',$parameters['start'])),array('required')));
		
		$startofmonth = (isset($parameters['startofmonth']))? $parameters['startofmonth'] : mktime(00, 00, 00, date('m'), 01, date('Y'));
		$endofmonth = (isset($parameters['endofmonth']))? $parameters['endofmonth'] : mktime(00, 00, 00, date('m') + 1 , 01, date('Y')) - 1;
		
		for($i = $startofmonth; $i < $endofmonth; $i += 86400){
			$boxes[$i]['name'] = date('d/m/Y',$i);
			$boxes[$i]['selected'] = false;
		}
		$form->addField(new checkboxgroupField('day', 'Dag', $boxes));
		
		$form->addField(new hiddenField('startofmonth', $startofmonth));
		$form->addField(new hiddenField('endofmonth', $endofmonth));

		if($form->validate()){
			
			
				if($form->getFieldvalue('start') < $form->getFieldvalue('stop')){
					$wachtdokterModel = new wachtdokterModel();
					
					$start = $form->getFieldvalue('start');
					$stop = $form->getFieldvalue('stop');
					
					// Overlap
						//contained
					$contained = array(
							'AND' => array(
										array('start' => array('mode' => '<','value' => $start)),
										array('stop' => array('mode' => '>', 'value' => $stop))
							)
						);
						//stop after start
					$stopstart = array(
							'AND' => array(
										array('start' => array('mode' => '<','value' => $start)),
										array('stop' => array('mode' => '>', 'value' => $start))
							)
						);
						//start before stop
					$startstop = array(
							'AND' => array(
										array('start' => array('mode' => '<','value' => $stop)),
										array('stop' => array('mode' => '>', 'value' => $stop))
							)
						);
						
					$big = array(
							'AND' => array(
										array('start' => array('mode' => '>','value' => $start)),
										array('stop' => array('mode' => '<', 'value' => $stop))
							)
						);

					$timecond = array('OR' => array($contained,$startstop,$stopstart,$big));
					
					$speccond = array('specialisme' => array('mode' => '=','value' => $parameters['specid']));
		
					$cond = array('AND' => array($speccond,$timecond));
					$test = $wachtdokterModel->get($cond);
					if(count($test) > 0){
						$this->response->assign('formerror_' . $form->getId() , 'innerHTML' , 'Er mogen geen overlappingen zijn.',true);
						$overlap = true;
					}
					else {
							$wachtdokter = new wachtdokterObject();
							
							$wachtdokter->setDokter($form->getFieldvalue('dokter'));
							$wachtdokter->setStart($form->getFieldvalue('start'));
							$wachtdokter->setStop($form->getFieldvalue('stop'));
							$wachtdokter->setSpecialisme($form->getFieldvalue('specid'));
							
							try{
								$wachtdokterModel->save($wachtdokter);
							}
							catch(Exception $e){			
								$flash = new popupController();
								$flash->createflash(array('name' => 'erroredit','type'=> 'error','content' => 'De gegevens werden niet aangepast! Raadpleeg de informaticadienst.'));				
							}
		
						$flash = new popupController();
						$flash->createflash(array('name' => 'flash_edit_' . $parameters['-gridid-'],'type' => 'success', 'content' => 'De gegevens zijn goed aangepast.'));
			
						$this->showspecdetails($parameters);
					}
				}
				else {
					$this->response->assign('formerror_' . $form->getId() , 'innerHTML' , 'Einduur moet na het beginuur liggen.',true);
				}	
			
		}
		elseif(!$form->isSent()){
			$view->assign('form',$form);
			
			$output = array();
			
			$closerequest = new ajaxrequest('mydoktervanwacht', 'showspecdetails', array('specid' => $parameters['specid'], 'startofmonth' => $parameters['startofmonth'], 'endofmonth' => $parameters['endofmonth']));
			$view->assign('closerequest',$closerequest);
			
			$wachtdokterModel = new wachtdokterviewModel();
			
			$speccond = array('specialisme' => array('mode' => '=','value' => $parameters['specid']));
			for($i = $startofmonth; $i < $endofmonth; $i += 86400){
				$start = array('start' => array('mode' => 'BETWEEN', 'value' => $i+1, 'topvalue' => ($i + 86400 -1)));
				$stop = array('stop' => array('mode' => 'BETWEEN', 'value' => $i, 'topvalue' => ($i + 86400 -1)));
				$cond = array('OR' => array($start,$stop));
				
				$dokters = $wachtdokterModel->get(array('AND' => array($speccond,$cond)));
				
				$tmp = array();
				$tmp['dokters'] = $dokters;
				$tmp['start'] = $i;
				
				$output[] = $tmp;
			}
			
			$view->assign('list',$output);
			$view->assign('specialisme',$mainspec[0]);
			
			
			
			$this->response->assign($this->self,'innerHTML',$view->fetch('mydoktervanwacht_edit.tpl'));
		}
		else {
			return false;
		}
	}
	
	public function deletedoktervanwacht($parameters = array()){
		$view = new ui($this);
		
		$wachtviewmodel = new wachtdokterviewModel();	
			
		$dok = $wachtviewmodel->getfromId($parameters['id']);
	 	if(count($dok) == 1) {
			if($parameters['sure'] != 'sure'){
				
				$view->assign('dokter',$dok[0]);
				
				$yes = new ajaxrequest('mydoktervanwacht', 'deletedoktervanwacht', array('id' => $parameters['id'], 'sure' => 'sure', 'specid' => $parameters['specid'], 'startofmonth' => $parameters['startofmonth'], 'endofmonth' => $parameters['endofmonth']));
				$no = new ajaxrequest('mydoktervanwacht', 'showspecdetails', array('specid' => $parameters['specid'], 'startofmonth' => $parameters['startofmonth'], 'endofmonth' => $parameters['endofmonth']));
				$view->assign('yes',$yes);
				$view->assign('no',$no);

				$this->response->assign('specgrid_' . $parameters['specid'],'innerHTML',$view->fetch('mydoktervanwacht_delete.tpl'));
				
			}
			else {
				
				$model = new wachtdokterModel();
	
				try{
					$model->deletebyId($parameters['id']);
				}
				catch( Exception $e){
					$flash = new popupController();
					$flash->createflash(array('name' => 'erroredit','type'=> 'error','content' => 'De gegevens werden niet aangepast! Raadpleeg de informaticadienst.'));
					
				}
	
				$flash = new popupController();
					$flash->createflash(array('name' => 'flash_del_' . $parameters['-gridid-'],'type' => 'success', 'content' => 'De gegevens zijn goed verwijderd.'));
	
				$this->showspecdetails(array('specid' => $parameters['specid'], 'startofmonth' => $parameters['startofmonth'], 'endofmonth' => $parameters['endofmonth']));
			}
		}
		else {
			$flash = new popupController();
			$flash->createflash(array('name' => 'flash_del_' . $parameters['-gridid-'],'type' => 'warning', 'content' => 'De gegevens werden niet teruggevonden.'));
	
		}
	}
}

?>