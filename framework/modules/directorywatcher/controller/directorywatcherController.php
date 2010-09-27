<?php

class directorywatcherController extends controller {

	public function showstatus($parameters = array()){
		$view = new ui($this);

		$grid = new mygrid('directorywatcher_status');
		$grid->setModel(new dirstatusModel());
		$grid->setDefaultconditions(array('reporttime' => array('mode' => 'maxvalue', 'value' => 'path')));
		$grid->setDefaultorder(array('fields' => array('path'), 'type' => 'ASC'));
		$grid->setPagesize(15);
		$grid->registerRequest('path', 'directorywatcher','pathdetails',array('id' => '{id}'));
		$view->assign('grid', $grid);

		$errorgrid = new mygrid('directorywatcher_status_error');
		$errorgrid->setModel(new dirstatuserrlistModel());
		$reportcond = array('reporttime' => array('mode' => 'maxvalue', 'value' => 'path'));
		$statuscond1 = array('status' => array('mode' => '<>','value' => 'ALL_OK'));
		$statuscond2 = array('status' => array('mode' => '<>','value' => ''));
		$statuscond = array('AND' => array($statuscond1,$statuscond2));
		$errorgrid->setDefaultconditions(array('AND' => array($reportcond,$statuscond)));
		$errorgrid->setDefaultorder(array('fields' => array('path'), 'type' => 'ASC'));
		$errorgrid->setPagesize(15);
		$errorgrid->registerRequest('path', 'directorywatcher','pathdetails',array('id' => '{oldid}'));
		$view->assign('errorgrid', $errorgrid);

		$deftreshold = new mygrid('deftreshold');
		$deftreshold->setModel(new directorywatchertresholdModel());
		$deftreshold->setDefaultconditions(array('path' => array('mode' => '=', 'value' => '_default_')));
		$deftreshold->registerEditrequest('directorywatcher','editTreshold',array('title' => 'Treshold aanpassen','id' => '{id}'));
		$view->assign('deftresholdgrid',$deftreshold);

		$dirstatusmodel = new dirstatusModel();
		$notprocessed = $dirstatusmodel->get(array('status' => array('mode' => '=','value' => '')));
		$view->assign('unprocessedcount', count($notprocessed));
		$view->assign('processrequest',new ajaxrequest('directorywatcher','processstatus'));

		$this->response->assign($this->self,'innerHTML',$view->fetch('directorywatcher_showstatus.tpl'));
	}

	public function processstatus($parameters = array()){
		$model = new dirstatusModel();
		$errmodel = new dirstatuserrlistModel();

		$statuses = $model->get(array('status' => array('mode' => '=', 'value' => '')));

		foreach($statuses as $object){
			$testmodel = new directorywatchertresholdModel();

			$currentresholds = $testmodel->getfromPath($object->getPath());
			if(count($currentresholds) == 0){

				$parent = $object->getParent();
				$parenttresholds = array();
				if($parent != ''){
					$parentobj = $model->getfromPath($parent);
					$parentobj = $parentobj[0];
					$parenttresholds = $testmodel->getfromPath($parent);
					while(count($parenttresholds) == 0 && $parent != ''){
						$parent = $parentobj->getParent();
						$parentobj = $model->getfromPath($parent);
						$parentobj = $parentobj[0];
						$parenttresholds = $testmodel->getfromPath($parent);
					}
				}

				if(count($parenttresholds) == 0 && $parent == ''){
					$parent = '_default_';
					$parenttresholds = $testmodel->getfromPath($parent);
				}

				$treshold = $parenttresholds[0];
			}
			else {
				$treshold = $currentresholds[0];
			}

			$object->setStatus('NO_TRESHOLD');
			if($treshold instanceof directorywatchertresholdObject){
				$object->setStatus('ALL_OK');

				$err = array();

				if($treshold->getNumfiles() > -1 && ($object->getNumfiles() > $treshold->getNumfiles())){
					$err[] = 'NUMFILES';
				}

				if($treshold->getLastfiletime() > -1 && $object->getLastfiletime() != 0 && ($object->getLastfiletime() < (time() - $treshold->getLastfiletime()))){
					$err[] = 'LASTFILETIME';
				}

				if($treshold->getOldestfiletime() > -1 && $object->getOldestfiletime() != 0 && ($object->getOldestfiletime() < (time() - $treshold->getOldestfiletime()))){
					$err[] = 'OLDESTFILETIME';
				}

				if($treshold->getExists() > -1 && ($object->getExists() != $treshold->getExists())){
					$err[] = 'EXISTS';
				}

				if(count($err) > 0){
					$object->setStatus('NOT_' . implode('_',$err));

					$errobject = new dirstatuserrlistObject();

					$errobject->setPath($object->getPath());
					$errobject->setExists($object->getExists());
					$errobject->setNumfiles($object->getNumfiles());
					$errobject->setLastfiletime($object->getLastfiletime());
					$errobject->setOldestfiletime($object->getOldestfiletime());
					$errobject->setParent($object->getParent());
					$errobject->setSubdirs($object->getSubdirs());
					$errobject->setReporttime($object->getReporttime());
					$errobject->setStatus($object->getStatus());

					$errobject->setOldid($object->getId());

					$test = $errmodel->getfromPath($errobject->getPath());
					if(count($test) == 1){
						$currerr = $test[0];
						if($currerr->getReporttime() < $errobject->getReporttime()){
							$errobject->setId($currerr->getId());
							$errmodel->save($errobject);
						}
					}
					else {
						$errmodel->save($errobject);
						if($treshold->getMail() == 1){

							$mail['subject'] = 'DirectoryWatcher: Fout in map ' . $errobject->getPath();
						    $mail['from'] = 'informatica@azlokeren.be';
						    $mail['Reply-To'] = $mail['from'];

						    $mail['message'] = '
	Om ' . date('d/m/Y - H:i' ,$errobject->getReporttime()) . ' werd er een probleem ontdekt voor de map ' . $errobject->getPath() . ' (' . $errobject->getStatus() . ')


	Bestaat: ' . $object->getExists() . '
	Aantal bestanden: ' . $object->getNumfiles() . '
	Laatst aangepast: ' . date('d/m/Y - H:i' ,$object->getLastfiletime()) . '
	Oudste bestand: ' . date('d/m/Y - H:i' ,$object->getOldestfiletime()) . '
	';

						    if(mail($treshold->getMailto() , $mail['subject'] , $mail['message'] , 'From: ' . $mail['from'] . "\r\n" . 'Reply-To: ' . $mail['Reply-To'], '-f ' . $mail['from'])){

						    }
						}
					}
				}
				else {
					$test = $errmodel->getfromPath($object->getPath());
					if(count($test) == 1){
						$currerr = $test[0];
						$errmodel->deletebyId($currerr->getId());

						if($treshold->getMail() == 1){

							$mail['subject'] = 'DirectoryWatcher: Fout in map ' . $object->getPath() . ' OPGELOST';
						    $mail['from'] = 'informatica@azlokeren.be';
						    $mail['Reply-To'] = $mail['from'];

						    $mail['message'] = '
	Om ' . date('d/m/Y - H:i' ,$object->getReporttime()) . ' was het probleem voor de map ' . $object->getPath() . ' opgelost.


	Bestaat: ' . $object->getExists() . '
	Aantal bestanden: ' . $object->getNumfiles() . '
	Laatst aangepast: ' . date('d/m/Y - H:i' ,$object->getLastfiletime()) . '
	';
					    if($object->getOldestfiletime() > 0) {
					    	$mail['message'] .= 'Oudste bestand: ' . date('d/m/Y - H:i' ,$object->getOldestfiletime());
					    }

						    if(mail($treshold->getMailto() , $mail['subject'] , $mail['message'] , 'From: ' . $mail['from'] . "\r\n" . 'Reply-To: ' . $mail['Reply-To'], '-f ' . $mail['from'])){

						    }
						}
					}
				}
			}

			$model->save($object);
		}

		$this->response->redirect('?pageid=' . myauth::getCurrentpageid());
	}

	public function editTreshold($parameters = array()){
		$view = new ui($this);

		$flash = new popupController();

		$model = new directorywatchertresholdModel();
		$tresholds = $model->getfromId($parameters['id']);

		if(count($tresholds) == 1){
			$treshold = $tresholds[0];

			$form  = new mygridform($parameters,$parameters['-gridid-'],'edit');
			$form->addField(new hiddenField('title',$parameters['title']));
			$form->addField(new hiddenField('id',$parameters['id']));

			$form->addField(new textField('numfiles','Aantal bestanden',$treshold->getNumfiles(),array('required','numeric')));
			$form->addField(new textField('lastfiletime','Laatst aangepast',$treshold->getLastfiletime(),array('required','numeric')));
			$form->addField(new textField('oldestfiletime','Oudste bestand',$treshold->getOldestfiletime(),array('required','numeric')));
			$form->addField(new textField('exists','Bestaat',$treshold->getExists(),array('required','numeric','range:-1<->1')));
			$form->addField(new textField('mail','Mail',$treshold->getMail(),array('required','numeric','range:0<->1')));
			$form->addField(new textField('mailto','Mail naar',$treshold->getMailto(),array('required')));

			if($form->validate()){

				$treshold->setNumfiles($form->getFieldvalue('numfiles'));
				$treshold->setLastfiletime($form->getFieldvalue('lastfiletime'));
				$treshold->setOldestfiletime($form->getFieldvalue('oldestfiletime'));
				$treshold->setExists($form->getFieldvalue('exists'));
				$treshold->setMail($form->getFieldvalue('mail'));
				$treshold->setMailto($form->getFieldvalue('mailto'));

				try {
					$model->save($treshold);
				}
				catch(Exception $e){
					$flash->createflash(array('name' => 'err', 'type' => 'error', 'content' => 'De gegevens werden niet aangepast.'));
					return false;
				}

				$flash->createflash(array('name' => 'success', 'type' => 'success', 'content' => 'De gegevens zijn goed aangepast.'));

				$this->response->assign('gridextra_' . $parameters['-gridid-'], 'innerHTML' , '');

				return true;
			}
			elseif(!$form->isSent()){
				$view->assign('form',$form);
				return $view->fetch('directorywatcher_edittreshold.tpl');
			}
			else {
				return false;
			}
		}
		else {
			$flash->createflash(array('name' => 'err', 'type' => 'error', 'content' => 'Deze treshold werd niet gevonden.'));
			return false;
		}
	}

	public function addTreshold($parameters = array()){
		$view = new ui($this);

		$flash = new popupController();

		$model = new directorywatchertresholdModel();
		$tresholds = $model->getfromPath('_default_');

		$cmodel = new dirstatusModel();
		$current = $cmodel->getfromId($parameters['currentid']);
		$current = $current[0];

		if(count($tresholds) == 1){
			$treshold = $tresholds[0];

			$form  = new mygridform($parameters,$parameters['-gridid-'],'edit');
			$form->addField(new hiddenField('title',$parameters['title']));
			$form->addField(new hiddenField('currentid',$parameters['currentid']));

			$form->addField(new textField('numfiles','Aantal bestanden',$treshold->getNumfiles(),array('required','numeric')));
			$form->addField(new textField('lastfiletime','Laatst aangepast',$treshold->getLastfiletime(),array('required','numeric')));
			$form->addField(new textField('oldestfiletime','Oudste bestand',$treshold->getOldestfiletime(),array('required','numeric')));
			$form->addField(new textField('exists','Bestaat',$treshold->getExists(),array('required','numeric','range:-1<->1')));
			$form->addField(new textField('mail','Mail',$treshold->getMail(),array('required','numeric','range:0<->1')));
			$form->addField(new textField('mailto','Mail naar',$treshold->getMailto(),array('required')));

			if($form->validate()){

				$newtreshold = new directorywatchertresholdObject();

				$newtreshold->setPath($current->getPath());
				$newtreshold->setNumfiles($form->getFieldvalue('numfiles'));
				$newtreshold->setLastfiletime($form->getFieldvalue('lastfiletime'));
				$newtreshold->setOldestfiletime($form->getFieldvalue('oldestfiletime'));
				$newtreshold->setExists($form->getFieldvalue('exists'));
				$newtreshold->setMail($form->getFieldvalue('mail'));
				$newtreshold->setMailto($form->getFieldvalue('mailto'));

				try {
					$model->save($newtreshold);
				}
				catch(Exception $e){
					$flash->createflash(array('name' => 'err', 'type' => 'error', 'content' => 'De gegevens werden niet aangepast.'));
					return false;
				}

				$flash->createflash(array('name' => 'success', 'type' => 'success', 'content' => 'De gegevens zijn goed aangepast.'));

				$this->response->assign('gridextra_' . $parameters['-gridid-'], 'innerHTML' , '');

				$grid = new mygrid($parameters['-gridid-']);
				$grid->unregisterRequest('-add-');
				$grid->registerEditrequest('directorywatcher','editTreshold',array('title' => 'Treshold aanpassen','id' => '{id}'));
				$grid->registerDeleterequest('directorywatcher','deleteTreshold',array('title' => 'Treshold verwijderen','id' => '{id}', 'currentid' => $current->getId()));
				$grid->setDefaultconditions(array('path' => array('mode' => '=', 'value' => $current->getPath())));

				return true;
			}
			elseif(!$form->isSent()){
				$view->assign('form',$form);
				return $view->fetch('directorywatcher_edittreshold.tpl');
			}
			else {
				return false;
			}
		}
		else {
			$flash->createflash(array('name' => 'err', 'type' => 'error', 'content' => 'De standaard treshold werd niet gevonden.'));
			return false;
		}
	}

	public function deleteTreshold($parameters = array()){
		$view = new ui($this);

		$flash = new popupController();

		$model = new directorywatchertresholdModel();
		$tresholds = $model->getfromId($parameters['id']);

		if(count($tresholds) == 1){
			$treshold = $tresholds[0];

			if($parameters['sure'] == 'sure'){
				try {
					$model->deletebyId($treshold->getId());
				}
				catch(Exception $e){
					$flash->createflash(array('name' => 'err', 'type' => 'error', 'content' => 'Deze treshold werd niet goed verwijderd.'));
					return false;
				}

				$flash->createflash(array('name' => 'success', 'type' => 'success', 'content' => 'De gegevens zijn goed verwijderd.'));
				$this->response->assign('gridextra_' . $parameters['-gridid-'], 'innerHTML' , '');

				$grid = new mygrid($parameters['-gridid-']);
				$grid->registerAddrequest('directorywatcher','addTreshold',array('title' => 'Treshold toevoegen','currentid' => $parameters['currentid']));
				$grid->unregisterRequest('-edit-');
				$grid->unregisterRequest('-delete-');

				$testmodel = new dirstatusModel();
				$current = $testmodel->getfromId($parameters['currentid']);
				$current = $current[0];

				$parent = $current->getParent();
				$parenttresholds = array();
				if($parent != ''){
					$parentobj = $testmodel->getfromPath($parent);
					$parentobj = $parentobj[0];
					$parenttresholds = $model->getfromPath($parent);
					while(count($parenttresholds) == 0 && $parent != ''){
						$parent = $parentobj->getParent();
						$parentobj = $testmodel->getfromPath($parent);
						$parentobj = $parentobj[0];
						$parenttresholds = $model->getfromPath($parent);
					}
				}

				if(count($parenttresholds) == 0 && $parent == ''){
					$parent = '_default_';
					$parenttresholds = $model->getfromPath($parent);
				}

				$grid->setDefaultconditions(array('path' => array('mode' => '=', 'value' => $parent)));


				return true;
			}
			else {
				$view->assign('treshold',$treshold);
				return $view->fetch('directorywatcher_deletetreshold.tpl');
			}
		}
		else {
			$flash->createflash(array('name' => 'err', 'type' => 'warning', 'content' => 'Deze treshold werd niet gevonden.'));
			return false;
		}
	}

	public function pathdetails($parameters = array()){
		$view = new ui($this);
		$flash = new popupController();

		$model = new dirstatusModel();

		$current = $model->getfromId($parameters['id']);

		if(count($current) == 1){
			$current = $current[0];
			$view->assign('current',$current);

			$closerequest = new ajaxrequest('mygrid', 'closeextra', array('id' => 'gridextra_' . $parameters['-gridid-']));
			$view->assign('closerequest', $closerequest);

			$history = new mygrid('directorywatcher_' . $current->getId());
			$history->setModel(new dirstatusModel());
			$history->setDefaultconditions(array('path' => array('mode' => '=', 'value' => $current->getPath())));
			$history->setDefaultorder(array('fields' => array('reporttime'), 'type' => 'DESC'));

			$view->assign('history',$history);

			$testmodel = new directorywatchertresholdModel();
			$treshold = new mygrid('treshold_' . $current->getId());
			$treshold->setModel($testmodel);

			$currentresholds = $testmodel->getfromPath($current->getPath());
			if(count($currentresholds) == 0){
				$treshold->registerAddrequest('directorywatcher','addTreshold',array('title' => 'Treshold toevoegen','currentid' => $current->getId()));

				$parent = $current->getParent();
				$parenttresholds = array();
				if($parent != ''){
					$parentobj = $model->getfromPath($parent);
					$parentobj = $parentobj[0];
					$parenttresholds = $testmodel->getfromPath($parent);
					while(count($parenttresholds) == 0 && $parent != ''){
						$parent = $parentobj->getParent();
						$parentobj = $model->getfromPath($parent);
						$parentobj = $parentobj[0];
						$parenttresholds = $testmodel->getfromPath($parent);
					}
				}

				if(count($parenttresholds) == 0 && $parent == ''){
					$parent = '_default_';
					$parenttresholds = $testmodel->getfromPath($parent);
				}

				$treshold->setDefaultconditions(array('path' => array('mode' => '=', 'value' => $parent)));
				$treshold->unregisterRequest('-edit-');
				$treshold->unregisterRequest('-delete-');
			}
			else {
				$treshold->setDefaultconditions(array('path' => array('mode' => '=', 'value' => $current->getPath())));

				$treshold->unregisterRequest('-add-');
				$treshold->registerEditrequest('directorywatcher','editTreshold',array('title' => 'Treshold aanpassen','id' => '{id}'));
				$treshold->registerDeleterequest('directorywatcher','deleteTreshold',array('title' => 'Treshold verwijderen','id' => '{id}', 'currentid' => $current->getId()));
			}

			$view->assign('tresholdgrid',$treshold);



			$this->response->assign('gridextra_' . $parameters['-gridid-'], 'innerHTML', $view->fetch('directorywatcher_pathdetails.tpl'));
			$this->response->script('var myFx = new Fx.Scroll(window).toElement(\'gridextra_' . $parameters['-gridid-'] .'\')');
		}
		else {
			$flash->createflash(array('name' => 'err', 'type' => 'error', 'content' => 'Dit path werd niet gevonden.'));
			return false;
		}
	}
}

?>