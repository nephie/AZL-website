<?php

//	Some constants that should be configured
define( 'DS' , '\\');
define( 'BASE_PATH' , 'D:' . DS . 'websites' . DS . 'azl' . DS . 'framework');
define( 'FRAMEWORK' , BASE_PATH );

//	Get the dispatcher
require_once(FRAMEWORK . DS . 'dispatcher.php');

//	And fire it up
try {
	//$disp = new dispatcher();
}
catch (Exception $e){
	echo $e->getMessage();
	echo $e->getTrace();
}

	$model = new dirstatusModel();
	$errmodel = new dirstatuserrlistModel();

	$statuses = $model->get(array('status' => array('mode' => '=', 'value' => '')));

	foreach($statuses as $object){
		echo 'processing ' . $object->getPath() . "\n";
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


Bestaat: ' . $errobject->getExists() . '
Aantal bestanden: ' . $errobject->getNumfiles() . '
Laatst aangepast: ' . date('d/m/Y - H:i' ,$errobject->getLastfiletime()) . '
Oudste bestand: ' . date('d/m/Y - H:i' ,$errobject->getOldestfiletime()) . '
';

					    if(mail($treshold->getMailto() , $mail['subject'] , $mail['message'] , 'From: ' . $mail['from'] . "\r\n" . 'Reply-To: ' . $mail['Reply-To'], '-f ' . $mail['from'])){
					    	echo 'Mailed '. "\n";
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
					    	echo 'Mailed ' . "\n";
					    }
					}
				}
			}
		}


		$model->save($object);
	}


	//Clean up old stuff
	$cond = array('reporttime' => array('mode' => '<' , 'value' => time() - 60*60*24*14));
	$model->delete($cond);
	$errmodel->delete($cond);
?>
