<?php

class medibridgeparserController extends controller
{
	public function listdokters($parameters = array())
	{
		$view = new ui($this);

		$grid = new mygrid('dokterlist_' . myauth::getCurrentpageid());

		$grid->setModel(new mbdokterModel());

		$grid->setDefaultorder(array('fields' => array('voornaam'), 'type' => 'ASC'));
		$grid->setDefaultpagesize(25);
		$grid->setDefaultconditions('');

		$view->assign('dokterlist' , $grid);

		$this->response->assign($this->self,'innerHTML', $view->fetch($parameters['viewprefix'] . 'medibridgeparser_listdokters.tpl') );
	}

	public function listlogs($parameters = array()){
		$view = new ui($this);

		$grid = new mygrid('loglist');

		$grid->setModel(new mbprocessedlogModel());

		$grid->setDefaultorder(array('fields' => array('parsedate'), 'type' => 'DESC'));
		$grid->setDefaultpagesize(15);
		$grid->setDefaultconditions('');

		$grid->registerRequest('filename' , 'medibridgeparser' , 'showLog' , array('logid' => '{id}'));

		$errorgrid = new mygrid('errorlist');

		$errorgrid->setModel(new mbprocessedlogModel());

		$errorgrid->setDefaultorder(array('fields' => array('parsedate'), 'type' => 'DESC'));
		$errorgrid->setDefaultpagesize(10);
		$errorgrid->setDefaultconditions(
						array( 'AND' => array(
											'OR' => array(
												array('statusdelivery' => array('mode' => '=', 'value' => 'DELIVERY_ERROR')),
												array('statusdelivery' => array('mode' => '=', 'value' => 'PARSER_ERROR')),
												array('statusdelivery' => array('mode' => '=', 'value' => 'NO_PARSER')),
												array('statusbackup' => array('mode' => '=', 'value' => 'BACKUP_ERROR')),
												array('statusbackup' => array('mode' => '=', 'value' => 'FILE_TIMEOUT')),
												array('statuserror' => array('mode' => '=', 'value' => 'ERROR_MOVE_ERROR'))
											),
											'statusdelivery' => array('mode' => '<>', 'value' => 'MESSAGE_IGNORED')
										)
						)
					);

		$errorgrid->registerRequest('filename' , 'medibridgeparser' , 'editMessage' , array('logid' => '{id}'));

		$view->assign('loglist' , $grid);
		$view->assign('errorlist', $errorgrid);


		$this->response->assign($this->self,'innerHTML', $view->fetch($parameters['viewprefix'] . 'medibridgeparser_listlogs.tpl') );
	}

	public function showLog($parameters = array()){
		$view = new ui($this);

		require(FRAMEWORK . DS . 'conf' . DS . 'medibridgeparser.php');

		$view->assign('basesourcemap', $basesourcemap);
		$view->assign('backupmap', $backupmap);
		$view->assign('errormap', $errormap);
		$view->assign('basedestinationmap', $basedestinationmap);

		$closerequest = new ajaxrequest('medibridgeparser', 'closeLog');
		$view->assign('closerequest', $closerequest);

		$pmodel = new mbprocessedlogModel();
		$model = new mblogModel();

		$log = $model->getfromId($parameters['logid']);
		$plog = $pmodel->getfromId($parameters['logid']);

		if(count($log) == 1){
			$log = $log[0];
		}

		if(count($plog) == 1){
			$plog = $plog[0];
		}

		$view->assign('log',$log);
		$view->assign('plog',$plog);

		$editrequest = new ajaxrequest('medibridgeparser', 'editMessage' , array('logid' => $log->getId()));

		$view->assign('editrequest', $editrequest);

		$this->response->assign('logcontainer','innerHTML', $view->fetch($parameters['viewprefix'] . 'medibridgeparser_showlog.tpl') );
	}

	public function editMessage($parameters = array()){

		$view = new ui($this);

		require(FRAMEWORK . DS . 'conf' . DS . 'medibridgeparser.php');

		$view->assign('basesourcemap', $basesourcemap);
		$view->assign('backupmap', $backupmap);
		$view->assign('errormap', $errormap);
		$view->assign('basedestinationmap', $basedestinationmap);

		$closerequest = new ajaxrequest('medibridgeparser', 'closeLog');
		$view->assign('closerequest', $closerequest);

		$pmodel = new mbprocessedlogModel();
		$model = new mblogModel();

		$log = $model->getfromId($parameters['logid']);
		$plog = $pmodel->getfromId($parameters['logid']);

		if(count($log) == 1){
			$log = $log[0];
		}

		if(count($plog) == 1){
			$plog = $plog[0];
		}

		$view->assign('log',$log);
		$view->assign('plog',$plog);

		$location = '';
		if($log->getStatusdelivery() == 'DELIVERY_SUCCESS' && $log->getStatusbackup() == 'BACKUP_SUCCES'){
			$location = $backupmap . '\\' . $log->getRelativebackuppath();
		}
		elseif($log->getStatusdelivery() == 'MESSAGE_RESEND' && $log->getStatusbackup() == 'BACKUP_SUCCES'){
			$location = $backupmap . '\\' . $log->getRelativebackuppath();
		}
		elseif($log->getStatuserror() == 'ERROR_MOVE_SUCCESS') {
			$location = $errormap . '\\' . $log->getRelativeerrorpath();
		}
		else {
			$location = 'GEEN BESTAND GEVONDEN!';
		}

		$destination = $basesourcemap . '\\' . $log->getRelativesourcepath();

		$view->assign('sourcelocation' , $location);
		$view->assign('destination',$destination);


		$file = utf8_encode(file_get_contents($location));

		$form = new form($parameters);
		$form->addField(new hiddenField('logid',$parameters['logid']));

		$doktermodel = new mbdokterModel();
		$dokters = $doktermodel->get(array(),array('fields' => array('achternaam') , 'mode' => 'ASC'));

		$select = new selectField('ontvanger' , 'Verzet ontvanger naar');
		$select->addOption(new selectoptionField('Niet verzetten', 'none'));
		foreach($dokters as $dokter){
			$select->addOption(new selectoptionField($dokter->getAchternaam() . ' ' . $dokter->getVoornaam() .' - ' . $dokter->getRiziv(), $dokter->getRiziv()));
		}

		$form->addField($select);

		$form->addField(new textareaField('content' , 'Bericht', $file , array('required')));

		$view->assign('form' , $form);

		$ignorerequest = new ajaxrequest('medibridgeparser','ignorelog',array('logid' => $parameters['logid']));
		$view->assign('ignore',$ignorerequest);

		if($form->validate()){
			try {
				file_put_contents($location,$form->getFieldvalue('content'));
				$arrcontent = file($location,FILE_IGNORE_NEW_LINES);

				if($form->getFieldvalue('ontvanger') != 'none'){
					if(stripos($location,'hdm-lab') !== false){
						$i = 0;
						foreach($arrcontent as $fileline){
							if(stripos($fileline,'A4') === 0){

								$parts = explode('\\',$fileline);
								$parts[2] = $form->getFieldvalue('ontvanger');

								$fileline = implode('\\',$parts);

								$arrcontent[$i] = rtrim($fileline);

								break;
							}
							else {
								$arrcontent[$i] = rtrim($fileline);
							}
							$i++;
						}
					}
					else {
						$arrcontent[7] = $form->getFieldvalue('ontvanger');
					}
				}

				$i = 0;
				foreach($arrcontent as $fileline){
					$arrcontent[$i] = rtrim($fileline);
					$i++;
				}


				file_put_contents($location,implode("\r\n",$arrcontent));

				if(!rename($location,$destination)){
					throw new Exception('Could not move file.');
				}

				$log->setStatusdelivery('MESSAGE_RESEND');
				$model->save($log);

				$this->response->redirect('?pageid=' . myauth::getCurrentpageid());
			}
			catch (Exception $e){
				$this->response->assign('formerror_' . $form->getId() , 'innerHTML' , 'Er was een probleem met het verwerken van de aanpassingen! (' . $e->getMessage() . ')',true);
			}
		}
		elseif(!$form->isSent()) {
			$this->response->assign('logcontainer','innerHTML', $view->fetch($parameters['viewprefix'] . 'medibridgeparser_editmessage.tpl') );
		}

	}

	public function ignorelog($parameters = array()){
		$model = new mblogModel();
		$log = $model->getfromId($parameters['logid']);
		if(count($log) == 1){
			$log = $log[0];

			$log->setStatusdelivery('MESSAGE_IGNORED');

			try{
				$model->save($log);
			}
			catch(Exception $e){}
		}
		$this->response->redirect('?pageid=' . myauth::getCurrentpageid());
	}

	public function closeLog($parameters = array()){
		$this->response->assign('logcontainer','innerHTML', '' );
	}

}
?>