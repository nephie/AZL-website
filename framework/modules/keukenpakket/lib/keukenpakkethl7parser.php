<?php

function keukenpakket_hl7_sort($a,$b){

//	$ratime = $a->getFieldvalue('EVN',6);
//	$rbtime = $b->getFieldvalue('EVN',6);
//
//	if($ratime == ''){
//		$ratime = $a->getFieldvalue('EVN',2);
//	}
//
//	if($rbtime == ''){
//		$rbtime = $b->getFieldvalue('EVN',2);
//	}
//
//	$atime = strtotime($ratime);
//	$btime = strtotime($rbtime);
//
//	if($atime == $btime){
//		$ratime = $a->getFieldvalue('EVN',2);
//		$rbtime = $b->getFieldvalue('EVN',2);
//
//		$atime = strtotime($ratime);
//		$btime = strtotime($rbtime);
//		if($atime == $btime){
//			return 0;
//		}
//		return ($atime < $btime)? -1 : 1;
//	}
//	return ($atime < $btime)? -1 : 1;

	$aid = $a->getFieldvalue('MSH',10);
	$bid = $b->getFieldvalue('MSH',10);

	if($aid == $bid){
		return 0;
	}
	return ($aid < $bid)? -1 : 1;
}

class keukenpakkethl7parser extends getandsetLib {
	public function processhl7messages(){
		require(FRAMEWORK . DS . 'conf' . DS . 'keukenpakket.php');

		$messages = array();
		$files = scandir($hl7dir);
		echo count($files) . ' files ';
		foreach($files as $filename){
			$filepath = $hl7dir . DS . $filename;
			if(is_file($filepath)){
				$tmp = new hl7Object();
				$string = file_get_contents($filepath,FILE_TEXT);

				if(!mb_detect_encoding($string)){

					$str = $string;

				    $c0 = ord($str[0]);
				    $c1 = ord($str[1]);

				    if ($c0 == 0xFE && $c1 == 0xFF) {
				        $be = true;
				    } else if ($c0 == 0xFF && $c1 == 0xFE) {
				        $be = false;
				    } else {
				        return $str;
				    }

				    $str = substr($str, 2);
				    $len = strlen($str);
				    $dec = '';
				    for ($i = 0; $i < $len; $i += 2) {
				        $c = ($be) ? ord($str[$i]) << 8 | ord($str[$i + 1]) :
				                ord($str[$i + 1]) << 8 | ord($str[$i]);
				        if ($c >= 0x0001 && $c < 0x007F) {
				            $dec .= chr($c);
				        } else if ($c > 0x07FF) {
				            $dec .= chr(0xE0 | (($c >> 12) & 0x0F));
				            $dec .= chr(0x80 | (($c >>  6) & 0x3F));
				            $dec .= chr(0x80 | (($c >>  0) & 0x3F));
				        } else {
				            $dec .= chr(0xC0 | (($c >>  6) & 0x1F));
				            $dec .= chr(0x80 | (($c >>  0) & 0x3F));
				        }
				    }

				    $string = $dec;
				}

				if(substr($string, 0,3) == pack("CCC",0xef,0xbb,0xbf)) { // Remove the UTF8 BOM if present
					$string=substr($string, 3);
				}
				$tmp->importData($string);
				$tmp->setSourcepath($filepath);
				$messages[] = $tmp;
			}
		}
		usort($messages,keukenpakket_hl7_sort);

//		//Group by patiënt
//		$patmessages = array();
//		foreach($messages as $message){
//			$patmessages[$message->getFieldvalue('PID',3)][] = $message;
//		}
//
//		foreach($patmessages as $id => $messages){
//		echo $id . '<br />';
		foreach($messages as $message){
			if($this->checkiflast($message)){	// If there is allready a movement present that took place after this one, it shouldn't be here
				switch(strtoupper($message->getFieldvalue('EVN',1))){ // Event type code
					case 'A01': $this->processA01Message($message); // Opname
						break;
					case 'A02': $this->processA02Message($message); // Verplaatsing
						break;
					case 'A03': $this->processA03Message($message); // Ontslag
						break;
					case 'A08': $this->processA08Message($message); // Update
						break;
					case 'A09': $this->processA09Message($message); // vertrek tijdelijke registratie
						break;
					case 'A10': $this->processA10Message($message); // terugkeer tijdelijke registratie
						break;
					case 'A11': $this->processA11Message($message); // annulatie dossier
						break;
					case 'A12': $this->processA12Message($message); // annulatie verplaatsing
						break;
					case 'A13': $this->processA13Message($message); // annulatie ontslag
						break;
					case 'A31': $this->processA31Message($message); // update patientgegevens
						break;
					case 'A40': $this->processA40Message($message); // Verwijderen patiënt
						break;
					case 'A45': $this->processA45Message($message); // verhangen dossier
						break;
					default: $this->ignoreMessage($message, 'geen te verwerken type'); // ignored
						break;
				}
			}
			else {
				$this->errorMessage($message, 'movements happened after this one');
			}
		}
//		echo '<br /> <br />';
//		}
	}

	public function checkmessages(){
		require(FRAMEWORK . DS . 'conf' . DS . 'keukenpakket.php');

		$messages = array();
		$files = scandir($hl7checkdir);
		echo count($files) . ' files ';
		foreach($files as $filename){
			$filepath = $hl7checkdir . DS . $filename;
			if(is_file($filepath)){
				$tmp = new hl7Object();
				$string = file_get_contents($filepath,FILE_TEXT);

				if(!mb_detect_encoding($string)){

					$str = $string;

				    $c0 = ord($str[0]);
				    $c1 = ord($str[1]);

				    if ($c0 == 0xFE && $c1 == 0xFF) {
				        $be = true;
				    } else if ($c0 == 0xFF && $c1 == 0xFE) {
				        $be = false;
				    } else {
				        return $str;
				    }

				    $str = substr($str, 2);
				    $len = strlen($str);
				    $dec = '';
				    for ($i = 0; $i < $len; $i += 2) {
				        $c = ($be) ? ord($str[$i]) << 8 | ord($str[$i + 1]) :
				                ord($str[$i + 1]) << 8 | ord($str[$i]);
				        if ($c >= 0x0001 && $c < 0x007F) {
				            $dec .= chr($c);
				        } else if ($c > 0x07FF) {
				            $dec .= chr(0xE0 | (($c >> 12) & 0x0F));
				            $dec .= chr(0x80 | (($c >>  6) & 0x3F));
				            $dec .= chr(0x80 | (($c >>  0) & 0x3F));
				        } else {
				            $dec .= chr(0xC0 | (($c >>  6) & 0x1F));
				            $dec .= chr(0x80 | (($c >>  0) & 0x3F));
				        }
				    }

				    $string = $dec;
				}

				if(substr($string, 0,3) == pack("CCC",0xef,0xbb,0xbf)) { // Remove the UTF8 BOM if present
					$string=substr($string, 3);
				}
				$tmp->importData($string);
				$tmp->setSourcepath($filepath);
				$messages[] = $tmp;
			}
		}
		usort($messages,keukenpakket_hl7_sort);

//		//Group by patiënt
//		$patmessages = array();
//		foreach($messages as $message){
//			$patmessages[$message->getFieldvalue('PID',3)][] = $message;
//		}


		echo '<pre>' . print_r($messages,true) . '</pre>';
	}

	protected function converttotime($rtime){
		$year = substr($rtime,0,4);
		$month = substr($rtime,4,2);
		$day = substr($rtime,6,2);
		$hour = substr($rtime,8,2);
		$minute = substr($rtime,10,2);
		$second = substr($rtime,12,2);

		return mktime($hour,$minute,$second,$month,$day,$year);
	}

	protected function checkiflast(hl7Object $message){
		if ($message->getFieldvalue('PV1',45) == ''){
			$messageid = $message->getFieldvalue('MSH',10);

			$reventtime = $message->getFieldvalue('EVN',6);
			$rmessagetime = $message->getFieldvalue('EVN',2);
			$eventtime = $this->converttotime($reventtime);
			$messagetime = $this->converttotime($rmessagetime);

			list($patnr,$null,$null,$facility) = explode($message->getFieldseperator2(),$message->getFieldvalue('PID',3));

			$patcond = array('patientnr' => array('mode' => '=', 'value' => $patnr));

			$testcond = $patcond;

			$model = new keukenpatientModel();
			$test = $model->get($testcond);

			if(count($test) > 0){
				$test = $test[0];

				$testeventtime = $test->getLasteventtime();
				$testmessagetime = $test->getLastmessagetime();
				$testmessageid = $test->getLastmessageid();

				if($reventtime != '' && $testeventtime != 0){
					if($eventtime > $testeventtime){
						return true;
					}
					elseif($eventtime < $testeventtime){
						return false;
					}
					else {
						// Same eventtime! switch to messageid
						if($message > $testmessageid){
							return true;
						}
						else {
							return false;
						}
					}
				}
				else {
					if($messagetime > $testmessagetime){
						return true;
					}
					elseif($messagetime < $testmessagetime){
						return false;
					}
					else {
						// Same messagetime! switch to messageid
						if($message > $testmessageid){
							return true;
						}
						else {
							return false;
						}
					}
				}
			}
			else {
				return true;
			}
		}
		else {
			// Geen actief dossier, zal later gefilterd worden
			return true;
		}
	}
/*
	protected function registermovement($message){
		$object = new hl7movementObject();

		$type = strtoupper($message->getFieldvalue('EVN',1));
		$rtime = $message->getFieldvalue('EVN',6);
		if($rtime == ''){
			$rtime = $message->getFieldvalue('EVN',2);
		}
		$time = strtotime($rtime);


		$naam = $message->getFieldvalue('PID',5);
		list($achternaam,$voornaam) = explode($message->getFieldseperator2(),$naam);

		$dossnr = $message->getFieldvalue('PV1',19);
		list($patnr,$null,$null,$facility) = explode($message->getFieldseperator2(),$message->getFieldvalue('PID',3));

		list($unit, $kamer,$bed,$campus) = explode($message->getFieldseperator2(),$message->getFieldvalue('PV1',3));
		list($punit, $pkamer,$pbed,$pcampus) = explode($message->getFieldseperator2(),$message->getFieldvalue('PV1',6));

		if($type == 'A03' || $type == 'A13' ){
			$punit = $unit;
			$pkamer = $kamer;
			$pbed = $bed;
			$pcampus = $campus;
		}

		$object->setType($type);
		$object->setTime($time);
		$object->setPatientnr($patnr);
		$object->setDossiernr($dossnr);
		$object->setVoornaam($voornaam);
		$object->setAchternaam($achternaam);
		$object->setKamer($kamer);
		$object->setBed($bed);
		$object->setCampus($campus);
		$object->setPkamer($pkamer);
		$object->setPbed($pbed);
		$object->setPcampus($pcampus);

		$model = new hl7movementModel();

		$test = $model->getfromPatientnr($patnr);


		if(count($test) > 0){	// Enkel de laatste beweging per patiënt bijhouden.
			$test = $test[0];
			$object->setId($test->getId());

			if($test->getType == 'A01' || $test->getType() == 'A02'){
				if($test->getCampus != 999 && $test->getBed() == 0){
					// A02 after A01/A02 without bed, update the A01/A02 to include the bed
					$test->setBed($object->getBed());
					$object = $test;
				}
			}
		}

		try{
			$model->save($object);
		}
		catch(Exception $e){
				echo $e->getMessage();
				return false;
		}
	}
*/
	protected function processA01Message(hl7Object $message){
		require(FRAMEWORK . DS . 'conf' . DS . 'keukenpakket.php');

		$naam = $message->getFieldvalue('PID',5);
		list($achternaam,$voornaam) = explode($message->getFieldseperator2(),$naam);
		$dossnr = $message->getFieldvalue('PV1',19);
		list($patnr,$null,$null,$facility) = explode($message->getFieldseperator2(),$message->getFieldvalue('PID',3));
		list($unit, $kamer,$bed,$campus) = explode($message->getFieldseperator2(),$message->getFieldvalue('PV1',3));
		$geslacht = $message->getFieldvalue('PID',8);
		$geboortedatum = $this->converttotime($message->getFieldvalue('PID',7));

		if($message->getFieldvalue('PV1',3) == ''){
			//Ambulant
			$this->finishMessage($message);
			return 0;
		}

		if($message->getFieldvalue('PV1',45) != ''){
			// Update van een reeds afgesloten dossier/verpleegperiod
			$this->finishMessage($message);
			return 0;
		}


		echo 'Opname ' . $naam . ' (' . $dossnr . ') VE: ' . $unit . ' Kamer: '. $kamer . ' Bed: ' . $bed . ' Campus: ' . $campus . '<br />';

		$patientObject = new keukenpatientObject();
		$patientObject->setVoornaam($voornaam);
		$patientObject->setAchternaam($achternaam);
		$patientObject->setCurrentdossiernr($dossnr);
		$patientObject->setPatientnr($patnr);
		$patientObject->setGeslacht($geslacht);
		$patientObject->setGeboortedatum($geboortedatum);
		$patientObject->setKamer($kamer);
		$patientObject->setBed($bed);
		$patientObject->setCampus($campus);
		$patientObject->setVe($unit);
		$patientObject->setLastmessageid($message->getFieldvalue('MSH',10));
		$patientObject->setLasteventtime($this->converttotime($message->getFieldvalue('EVN',6)));
		$patientObject->setLastmessagetime($this->converttotime($message->getFieldvalue('EVN',2)));

		if($message->getFieldvalue('PV1',7) != ''){
			list($dokterognummer,$dokternaam,$doktervoornaam, $null,$null, $dokterprefix) = explode($message->getFieldseperator2(),$message->getFieldvalue('PV1',7));
			$patientObject->setDokterognummer($dokterognummer);
			$patientObject->setDokternaam($dokterprefix . ' ' . $dokternaam . ' ' . $doktervoornaam);
		}

		$model = new keukenpatientModel();
		$test = $model->getfromPatientnr($patnr);
		if(count($test) > 0){
			$patientObject->setId($test[0]->getId());
		}

		try {
			$model->save($patientObject);
		}
		catch(Exception $e) {
			$this->errorMessage($message, 'could not save: ' . $e->getMessage() );
			return false;
		}

//		// register it
//		$this->registermovement($message);

		// We made it!
		$this->finishMessage($message);


	}

	protected function processA02Message(hl7Object $message){
		require(FRAMEWORK . DS . 'conf' . DS . 'keukenpakket.php');

		$naam = $message->getFieldvalue('PID',5);
		list($achternaam,$voornaam) = explode($message->getFieldseperator2(),$naam);
		$dossnr = $message->getFieldvalue('PV1',19);
		list($patnr,$null,$null,$facility) = explode($message->getFieldseperator2(),$message->getFieldvalue('PID',3));
		list($unit, $kamer,$bed,$campus) = explode($message->getFieldseperator2(),$message->getFieldvalue('PV1',3));
		list($punit, $pkamer,$pbed,$pcampus) = explode($message->getFieldseperator2(),$message->getFieldvalue('PV1',6));
		$geslacht = $message->getFieldvalue('PID',8);
		$geboortedatum = $this->converttotime($message->getFieldvalue('PID',7));

		if($message->getFieldvalue('PV1',3) == ''){
			//Ambulant
			$this->finishMessage($message);
			return 0;
		}

		if($message->getFieldvalue('PV1',45) != ''){
			// Update van een reeds afgesloten dossier/verpleegperiod
			$this->finishMessage($message);
			return 0;
		}

		//robuuster
		$model = new keukenpatientModel();
		$test = $model->getfromPatientnr($patnr);
		$process = true;

		if(count($test) > 0){
			$test = $test[0];
			$tkamer = $test->getKamer();
			$tbed = $test->getBed();
			$tcampus = $test->getCampus();

			if($tcampus == $campus && $tkamer == $kamer && $tbed == $bed){
				$process = false;
			}

		}
		else {
			// Just do it
		}

		if($process){

				echo 'Relocatie ' . $naam . ' (' . $dossnr . ') VE: ' . $unit . ' Kamer: '. $kamer . ' Bed: ' . $bed . ' Campus: ' . $campus . '( From: VE: ' . $punit . ' Kamer: '. $pkamer . ' Bed: ' . $pbed . ' Campus: ' . $pcampus . ')<br />';

				$patientObject = new keukenpatientObject();
				$patientObject->setVoornaam($voornaam);
				$patientObject->setAchternaam($achternaam);
				$patientObject->setCurrentdossiernr($dossnr);
				$patientObject->setPatientnr($patnr);
				$patientObject->setGeslacht($geslacht);
				$patientObject->setGeboortedatum($geboortedatum);
				$patientObject->setKamer($kamer);
				$patientObject->setBed($bed);
				$patientObject->setCampus($campus);
				$patientObject->setVe($unit);
				$patientObject->setLastmessageid($message->getFieldvalue('MSH',10));
				$patientObject->setLasteventtime($this->converttotime($message->getFieldvalue('EVN',6)));
				$patientObject->setLastmessagetime($this->converttotime($message->getFieldvalue('EVN',2)));

				if($message->getFieldvalue('PV1',7) != ''){
					list($dokterognummer,$dokternaam,$doktervoornaam, $null,$null, $dokterprefix) = explode($message->getFieldseperator2(),$message->getFieldvalue('PV1',7));
					$patientObject->setDokterognummer($dokterognummer);
					$patientObject->setDokternaam($dokterprefix . ' ' . $dokternaam . ' ' . $doktervoornaam);
				}

				$model = new keukenpatientModel();
				$test = $model->getfromPatientnr($patnr);
				if(count($test) > 0){
					$patientObject->setId($test[0]->getId());

					try {
						$model->save($patientObject);
					}
					catch(Exception $e) {
						$this->errorMessage($message, 'could not save: ' . $e->getMessage() );
						return false;
					}


//						// register it
//						$this->registermovement($message);

				}
				else {
					try {
						$model->save($patientObject);
					}
					catch(Exception $e) {
						$this->errorMessage($message, 'could not save: ' . $e->getMessage() );
						return false;
					}


//						// register it
//						$this->registermovement($message);

				}
			}


		// We made it!
		$this->finishMessage($message);
	}

	protected function processA03Message(hl7Object $message){
		require(FRAMEWORK . DS . 'conf' . DS . 'keukenpakket.php');

		$naam = $message->getFieldvalue('PID',5);
		$dossnr = $message->getFieldvalue('PV1',19);

		list($unit, $kamer,$bed,$campus) = explode($message->getFieldseperator2(),$message->getFieldvalue('PV1',3));

		echo 'Ontslag ' . $naam . ' (' . $dossnr . ') VE: ' . $unit . ' Kamer: '. $kamer . ' Bed: ' . $bed . ' Campus: ' . $campus . '<br />';

		$model = new keukenpatientModel();
		$patient = $model->getfromCurrentdossiernr($dossnr);

		if(count($patient) == 1){
			$patient = $patient[0];

			if($patient->getKamer() != 0 || $patient->getCampus() != '999' || $patient->getBed() != 0){
				$patient->setKamer(0);
				$patient->setBed(0);
				$patient->setCampus(999);
				$patient->setVe('');
				$patient->setVerplaatsing('');
				$patient->setLastmessageid($message->getFieldvalue('MSH',10));
				$patient->setLasteventtime('');
				$patient->setLastmessagetime($this->converttotime($message->getFieldvalue('EVN',2)));

				$patient->setDokterognummer('');
				$patient->setDokternaam('');


				try{
					$model->save($patient);
				}
				catch(Exception $e){
					$this->errorMessage($message, 'could not save: ' . $e->getMessage() );
					return false;
				}

//				// register it
//				$this->registermovement($message);
			}
		}

		// We made it!
		$this->finishMessage($message);
	}

	protected function processA08Message(hl7Object $message){
		require(FRAMEWORK . DS . 'conf' . DS . 'keukenpakket.php');

		$naam = $message->getFieldvalue('PID',5);
		list($achternaam,$voornaam) = explode($message->getFieldseperator2(),$naam);
		$dossnr = $message->getFieldvalue('PV1',19);
		list($patnr,$null,$null,$facility) = explode($message->getFieldseperator2(),$message->getFieldvalue('PID',3));
		list($unit, $kamer,$bed,$campus) = explode($message->getFieldseperator2(),$message->getFieldvalue('PV1',3));
		$campus = ($campus != '')? $campus: 999;
		$geslacht = $message->getFieldvalue('PID',8);
		$geboortedatum = $this->converttotime($message->getFieldvalue('PID',7));

		$einddatum = $message->getFieldvalue('PV1',45);

		if($message->getFieldvalue('PV1',3) == ''){
			//Ambulant
			$this->finishMessage($message);
			return 0;
		}


		$model = new keukenpatientModel();

		if($einddatum == ''){ // Update van lopend dossier


//				$test = $model->getfromPatientnr($patnr);
//				$new = false;
//				$move = false;
//				if(count($test) > 0){
//					$test = $test[0];
//					if($test->getKamer() != $kamer || $test->getCampus() != $campus || $test->getBed() != $bed){
//						if($test->getKamer() != 0){
//							$new = true;
//						}
//						else {
//							$move = true;
//						}
//					}
//				}
//				else {
//					$new = true;
//				}


					echo 'Update ' . $naam . ' (' . $dossnr . ') VE: ' . $unit . ' Kamer: '. $kamer . ' Bed: ' . $bed . ' Campus: ' . $campus . '<br />';

					$patientObject = new keukenpatientObject();
					$patientObject->setVoornaam($voornaam);
					$patientObject->setAchternaam($achternaam);
					$patientObject->setCurrentdossiernr($dossnr);
					$patientObject->setPatientnr($patnr);
					$patientObject->setGeslacht($geslacht);
					$patientObject->setGeboortedatum($geboortedatum);
					$patientObject->setKamer($kamer);
					$patientObject->setBed($bed);
					$patientObject->setCampus($campus);
					$patientObject->setVe($unit);
					$patientObject->setLastmessageid($message->getFieldvalue('MSH',10));
					$patientObject->setLasteventtime('');
					$patientObject->setLastmessagetime($this->converttotime($message->getFieldvalue('EVN',2)));

					if($message->getFieldvalue('PV1',7) != ''){
						list($dokterognummer,$dokternaam,$doktervoornaam, $null,$null, $dokterprefix) = explode($message->getFieldseperator2(),$message->getFieldvalue('PV1',7));
						$patientObject->setDokterognummer($dokterognummer);
						$patientObject->setDokternaam($dokterprefix . ' ' . $dokternaam . ' ' . $doktervoornaam);
					}

					$test = $model->getfromPatientnr($patnr);
					if(count($test) > 0){
						$patientObject->setId($test[0]->getId());
					}

					try {
						$model->save($patientObject);
					}
					catch(Exception $e) {
						$this->errorMessage($message, 'could not save: ' . $e->getMessage() );
						return false;
					}

//					// register it
//					if($new){
//						$message->changetype('A01');
//						$this->registermovement($message);
//					}
//					elseif($move){
//						$message->changetype('A02');
//						$this->registermovement($message);
//					}




		}
		else { // Update van afgesloten dossier, behandel als ontslag
			$patient = $model->getfromCurrentdossiernr($dossnr);

			if(count($patient) == 1){
				$patient = $patient[0];

				if($patient->getKamer() != 0 || $patient->getCampus() != '999' || $patient->getBed() != 0){

					$patient->setKamer(0);
					$patient->setBed(0);
					$patient->setCampus(999);
					$patient->setVe('');
					$patient->setVerplaatsing('');
					$patient->setLastmessageid($message->getFieldvalue('MSH',10));
					$patient->setLasteventtime('');
					$patient->setLastmessagetime($this->converttotime($message->getFieldvalue('EVN',2)));

					$patient->setDokterognummer('');
					$patient->setDokternaam('');

					try{
						$model->save($patient);
					}
					catch(Exception $e){
						$this->errorMessage($message, 'could not save: ' . $e->getMessage() );
						return false;
					}
//					// register it
//					$message->changetype('A03');
//					$this->registermovement($message);
				}
			}


		}

		// We made it!
			$this->finishMessage($message);
	}

	protected function processA09Message(hl7Object $message ){
		require(FRAMEWORK . DS . 'conf' . DS . 'keukenpakket.php');

		$naam = $message->getFieldvalue('PID',5);
		list($achternaam,$voornaam) = explode($message->getFieldseperator2(),$naam);
		$dossnr = $message->getFieldvalue('PV1',19);
		list($patnr,$null,$null,$facility) = explode($message->getFieldseperator2(),$message->getFieldvalue('PID',3));
		list($unit, $kamer,$bed,$campus) = explode($message->getFieldseperator2(),$message->getFieldvalue('PV1',3));
		$geslacht = $message->getFieldvalue('PID',8);
		$geboortedatum = $this->converttotime($message->getFieldvalue('PID',7));
		$verplaatsing = $message->getFieldvalue('PV1',11);

		$einddatum = $message->getFieldvalue('PV1',45);

		if($message->getFieldvalue('PV1',3) == ''){
			//Ambulant
			$this->finishMessage($message);
			return 0;
		}

		$model = new keukenpatientModel();

		if($einddatum == ''){ // Update van lopend dossier
			if($kamer == 0  || $campus == 0 || $bed == 0) {
				//	Zonder kamer, bed of campus kan ik niets doen!
				$this->errorMessage($message, 'geen kamer/bed/campus');
			}
			else {

//				$test = $model->getfromPatientnr($patnr);
//				$new = false;
//				$move = false;
//				if(count($test) > 0){
//					$test = $test[0];
//					if($test->getKamer() != $kamer || $test->getCampus() != $campus || $test->getBed() != $bed){
//						if($test->getKamer() != 0){
//							$new = true;
//						}
//						else {
//							$move = true;
//						}
//					}
//				}
//				else {
//					$new = true;
//				}


					echo 'Update ' . $naam . ' (' . $dossnr . ') VE: ' . $unit . ' Kamer: '. $kamer . ' Bed: ' . $bed . ' Campus: ' . $campus . '<br />';

					$patientObject = new keukenpatientObject();
					$patientObject->setVoornaam($voornaam);
					$patientObject->setAchternaam($achternaam);
					$patientObject->setCurrentdossiernr($dossnr);
					$patientObject->setPatientnr($patnr);
					$patientObject->setGeslacht($geslacht);
					$patientObject->setGeboortedatum($geboortedatum);
					$patientObject->setKamer($kamer);
					$patientObject->setBed($bed);
					$patientObject->setCampus($campus);
					$patientObject->setVe($unit);
					$patientObject->setVerplaatsing($verplaatsing);
					$patientObject->setLastmessageid($message->getFieldvalue('MSH',10));
					$patientObject->setLasteventtime($this->converttotime($message->getFieldvalue('EVN',6)));
					$patientObject->setLastmessagetime($this->converttotime($message->getFieldvalue('EVN',2)));

					if($message->getFieldvalue('PV1',7) != ''){
						list($dokterognummer,$dokternaam,$doktervoornaam, $null,$null, $dokterprefix) = explode($message->getFieldseperator2(),$message->getFieldvalue('PV1',7));
						$patientObject->setDokterognummer($dokterognummer);
						$patientObject->setDokternaam($dokterprefix . ' ' . $dokternaam . ' ' . $doktervoornaam);
					}

					$test = $model->getfromPatientnr($patnr);
					if(count($test) > 0){
						$patientObject->setId($test[0]->getId());
					}

					try {
						$model->save($patientObject);
					}
					catch(Exception $e) {
						$this->errorMessage($message, 'could not save: ' . $e->getMessage() );
						return false;
					}

//					// register it
//					if($new){
//						$message->changetype('A01');
//						$this->registermovement($message);
//					}
//					elseif($move){
//						$message->changetype('A02');
//						$this->registermovement($message);
//					}



			}
		}
		else { // Update van afgesloten dossier

		}

		// We made it!
			$this->finishMessage($message);
	}

	protected function processA10Message(hl7Object $message ){
		require(FRAMEWORK . DS . 'conf' . DS . 'keukenpakket.php');

		$naam = $message->getFieldvalue('PID',5);
		list($achternaam,$voornaam) = explode($message->getFieldseperator2(),$naam);
		$dossnr = $message->getFieldvalue('PV1',19);
		list($patnr,$null,$null,$facility) = explode($message->getFieldseperator2(),$message->getFieldvalue('PID',3));
		list($unit, $kamer,$bed,$campus) = explode($message->getFieldseperator2(),$message->getFieldvalue('PV1',3));
		$geslacht = $message->getFieldvalue('PID',8);
		$geboortedatum = $this->converttotime($message->getFieldvalue('PID',7));
		$verplaatsing = $message->getFieldvalue('PV1',11);

		$einddatum = $message->getFieldvalue('PV1',45);

		if($message->getFieldvalue('PV1',3) == ''){
			//Ambulant
			$this->finishMessage($message);
			return 0;
		}

		$model = new keukenpatientModel();

		if($einddatum == ''){ // Update van lopend dossier
			if($kamer == 0  || $campus == 0 || $bed == 0) {
				//	Zonder kamer, bed of campus kan ik niets doen!
				$this->errorMessage($message, 'geen kamer/bed/campus');
			}
			else {

//				$test = $model->getfromPatientnr($patnr);
//				$new = false;
//				$move = false;
//				if(count($test) > 0){
//					$test = $test[0];
//					if($test->getKamer() != $kamer || $test->getCampus() != $campus || $test->getBed() != $bed){
//						if($test->getKamer() != 0){
//							$new = true;
//						}
//						else {
//							$move = true;
//						}
//					}
//				}
//				else {
//					$new = true;
//				}


					echo 'Update ' . $naam . ' (' . $dossnr . ') VE: ' . $unit . ' Kamer: '. $kamer . ' Bed: ' . $bed . ' Campus: ' . $campus . '<br />';

					$patientObject = new keukenpatientObject();
					$patientObject->setVoornaam($voornaam);
					$patientObject->setAchternaam($achternaam);
					$patientObject->setCurrentdossiernr($dossnr);
					$patientObject->setPatientnr($patnr);
					$patientObject->setGeslacht($geslacht);
					$patientObject->setGeboortedatum($geboortedatum);
					$patientObject->setKamer($kamer);
					$patientObject->setBed($bed);
					$patientObject->setCampus($campus);
					$patientObject->setVe($unit);
					$patientObject->setVerplaatsing($verplaatsing);
					$patientObject->setLastmessageid($message->getFieldvalue('MSH',10));
					$patientObject->setLasteventtime($this->converttotime($message->getFieldvalue('EVN',6)));
					$patientObject->setLastmessagetime($this->converttotime($message->getFieldvalue('EVN',2)));

					if($message->getFieldvalue('PV1',7) != ''){
						list($dokterognummer,$dokternaam,$doktervoornaam, $null,$null, $dokterprefix) = explode($message->getFieldseperator2(),$message->getFieldvalue('PV1',7));
						$patientObject->setDokterognummer($dokterognummer);
						$patientObject->setDokternaam($dokterprefix . ' ' . $dokternaam . ' ' . $doktervoornaam);
					}

					$test = $model->getfromPatientnr($patnr);
					if(count($test) > 0){
						$patientObject->setId($test[0]->getId());
					}

					try {
						$model->save($patientObject);
					}
					catch(Exception $e) {
						$this->errorMessage($message, 'could not save: ' . $e->getMessage() );
						return false;
					}

//					// register it
//					if($new){
//						$message->changetype('A01');
//						$this->registermovement($message);
//					}
//					elseif($move){
//						$message->changetype('A02');
//						$this->registermovement($message);
//					}



			}
		}
		else { // Update van afgesloten dossier

		}

		// We made it!
			$this->finishMessage($message);
	}

	protected function processA11Message(hl7Object $message){

		require(FRAMEWORK . DS . 'conf' . DS . 'keukenpakket.php');

		$naam = $message->getFieldvalue('PID',5);
		list($achternaam,$voornaam) = explode($message->getFieldseperator2(),$naam);
		$dossnr = $message->getFieldvalue('PV1',19);
		list($patnr,$null,$null,$facility) = explode($message->getFieldseperator2(),$message->getFieldvalue('PID',3));
		list($unit, $kamer,$bed,$campus) = explode($message->getFieldseperator2(),$message->getFieldvalue('PV1',3));
		$geslacht = $message->getFieldvalue('PID',8);
		$geboortedatum = $this->converttotime($message->getFieldvalue('PID',7));

		if($message->getFieldvalue('PV1',45) != ''){
			// Update van een reeds afgesloten dossier/verpleegperiod
			$this->finishMessage($message);
			return 0;
		}

			$model = new keukenpatientModel();
			$patient = $model->getfromCurrentdossiernr($dossnr);

			if(count($patient) == 1){
				$patient = $patient[0];

				if($patient->getKamer() != 0 || $patient->getCampus() != '999' || $patient->getBed() != 0){

					$patient->setKamer(0);
					$patient->setBed(0);
					$patient->setCampus(999);
					$patient->setVe('');
					$patient->setLastmessageid($message->getFieldvalue('MSH',10));
					$patient->setLasteventtime($this->converttotime($message->getFieldvalue('EVN',6)));
					$patient->setLastmessagetime($this->converttotime($message->getFieldvalue('EVN',2)));

					$patient->setDokterognummer('');
					$patient->setDokternaam('');

					try{
						$model->save($patient);
					}
					catch(Exception $e){
						$this->errorMessage($message, 'could not save: ' . $e->getMessage() );
						return false;
					}
				}
			}

		$this->finishMessage($message);
	}

	protected function processA12Message(hl7Object $message){
		require(FRAMEWORK . DS . 'conf' . DS . 'keukenpakket.php');

		$naam = $message->getFieldvalue('PID',5);
		list($achternaam,$voornaam) = explode($message->getFieldseperator2(),$naam);
		$dossnr = $message->getFieldvalue('PV1',19);
		list($patnr,$null,$null,$facility) = explode($message->getFieldseperator2(),$message->getFieldvalue('PID',3));
		list($unit, $kamer,$bed,$campus) = explode($message->getFieldseperator2(),$message->getFieldvalue('PV1',6));
		$geslacht = $message->getFieldvalue('PID',8);
		$geboortedatum = $this->converttotime($message->getFieldvalue('PID',7));

		if($message->getFieldvalue('PV1',3) == ''){
			//Ambulant
			$this->finishMessage($message);
			return 0;
		}

		if($message->getFieldvalue('PV1',45) != ''){
			// Update van een reeds afgesloten dossier/verpleegperiod
			$this->finishMessage($message);
			return 0;
		}


			$model = new keukenpatientModel();
			$patient = $model->getfromCurrentdossiernr($dossnr);

			if(count($patient) == 1){
				$patient = $patient[0];

				echo 'Annulatie verplaatsing ' . $naam . ' (' . $dossnr . ') VE: ' . $unit . ' Kamer: '. $kamer . ' Bed: ' . $bed . ' Campus: ' . $campus . '<br />';

				$patientObject = new keukenpatientObject();
				$patientObject->setVoornaam($voornaam);
				$patientObject->setAchternaam($achternaam);
				$patientObject->setCurrentdossiernr($dossnr);
				$patientObject->setPatientnr($patnr);
				$patientObject->setGeslacht($geslacht);
				$patientObject->setGeboortedatum($geboortedatum);
				$patientObject->setKamer($kamer);
				$patientObject->setBed($bed);
				$patientObject->setCampus($campus);
				$patientObject->setVe($unit);
				$patientObject->setLastmessageid($message->getFieldvalue('MSH',10));
				$patientObject->setLasteventtime(0);
				$patientObject->setLastmessagetime($this->converttotime($message->getFieldvalue('EVN',2)));

				if($message->getFieldvalue('PV1',7) != ''){
					list($dokterognummer,$dokternaam,$doktervoornaam, $null,$null, $dokterprefix) = explode($message->getFieldseperator2(),$message->getFieldvalue('PV1',7));
					$patientObject->setDokterognummer($dokterognummer);
					$patientObject->setDokternaam($dokterprefix . ' ' . $dokternaam . ' ' . $doktervoornaam);
				}

				$model = new keukenpatientModel();
				$test = $model->getfromPatientnr($patnr);
				if(count($test) > 0){
					$patientObject->setId($test[0]->getId());
				}

				try {
					$model->save($patientObject);
				}
				catch(Exception $e) {
					$this->errorMessage($message, 'could not save: ' . $e->getMessage() );
					return false;
				}
			}

//			// register it
//			$this->registermovement($message);

			// We made it!
			$this->finishMessage($message);


	}

	protected function processA13Message(hl7Object $message){
		require(FRAMEWORK . DS . 'conf' . DS . 'keukenpakket.php');

		$naam = $message->getFieldvalue('PID',5);
		list($achternaam,$voornaam) = explode($message->getFieldseperator2(),$naam);
		$dossnr = $message->getFieldvalue('PV1',19);
		list($patnr,$null,$null,$facility) = explode($message->getFieldseperator2(),$message->getFieldvalue('PID',3));
		list($unit, $kamer,$bed,$campus) = explode($message->getFieldseperator2(),$message->getFieldvalue('PV1',6));
		$geslacht = $message->getFieldvalue('PID',8);
		$geboortedatum = $this->converttotime($message->getFieldvalue('PID',7));

		if($message->getFieldvalue('PV1',3) == ''){
			//Ambulant
			$this->finishMessage($message);
			return 0;
		}

		if($message->getFieldvalue('PV1',45) != ''){
			// Update van een reeds afgesloten dossier/verpleegperiod
			$this->finishMessage($message);
			return 0;
		}


			echo 'Annulatie ontslag ' . $naam . ' (' . $dossnr . ') VE: ' . $unit . ' Kamer: '. $kamer . ' Bed: ' . $bed . ' Campus: ' . $campus . '<br />';

			$patientObject = new keukenpatientObject();
			$patientObject->setVoornaam($voornaam);
			$patientObject->setAchternaam($achternaam);
			$patientObject->setCurrentdossiernr($dossnr);
			$patientObject->setPatientnr($patnr);
			$patientObject->setGeslacht($geslacht);
			$patientObject->setGeboortedatum($geboortedatum);
			$patientObject->setKamer($kamer);
			$patientObject->setBed($bed);
			$patientObject->setCampus($campus);
			$patientObject->setVe($unit);
			$patientObject->setLastmessageid($message->getFieldvalue('MSH',10));
			$patientObject->setLasteventtime($this->converttotime($message->getFieldvalue('EVN',6)));
			$patientObject->setLastmessagetime($this->converttotime($message->getFieldvalue('EVN',2)));

			if($message->getFieldvalue('PV1',7) != ''){
				list($dokterognummer,$dokternaam,$doktervoornaam, $null,$null, $dokterprefix) = explode($message->getFieldseperator2(),$message->getFieldvalue('PV1',7));
				$patientObject->setDokterognummer($dokterognummer);
				$patientObject->setDokternaam($dokterprefix . ' ' . $dokternaam . ' ' . $doktervoornaam);
			}

			$model = new keukenpatientModel();
			$test = $model->getfromPatientnr($patnr);
			if(count($test) > 0){
				$patientObject->setId($test[0]->getId());
			}

			try {
				$model->save($patientObject);
			}
			catch(Exception $e) {
				$this->errorMessage($message, 'could not save: ' . $e->getMessage() );
				return false;
			}

//			// register it
//			$this->registermovement($message);

			// We made it!
			$this->finishMessage($message);

	}

	protected function processA40Message(hl7Object $message){
		require(FRAMEWORK . DS . 'conf' . DS . 'keukenpakket.php');

		list($patnr,$null,$null,$facility) = explode($message->getFieldseperator2(),$message->getFieldvalue('PID',3));

		echo 'Verwijderen ' . $naam . ' (' . $patnr . ') <br />';

		$model = new keukenpatientModel();
		$patient = $model->getfrompatientnr($patnr);

		if(count($patient) == 1){
			$patient = $patient[0];

			try{
				$model->delete($patient);
			}
			catch(Exception $e){
				if( !rename($message->getSourcepath(),str_replace($hl7dir,$hl7errordir,$message->getSourcepath()))){
					echo 'error moving';
				}
				return false;
			}
		}

//		// register it
//		$this->registermovement($message);

		// We made it!
		$this->finishMessage($message);
	}

	protected function processA31Message(hl7Object $message){
		require(FRAMEWORK . DS . 'conf' . DS . 'keukenpakket.php');

		$naam = $message->getFieldvalue('PID',5);
		list($achternaam,$voornaam) = explode($message->getFieldseperator2(),$naam);

		list($patnr,$null,$null,$facility) = explode($message->getFieldseperator2(),$message->getFieldvalue('PID',3));
		$geslacht = $message->getFieldvalue('PID',8);
		$geboortedatum = $this->converttotime($message->getFieldvalue('PID',7));



			echo 'update ' . $naam . ' (' . $dossnr . ') <br />';


			$model = new keukenpatientModel();
			$test = $model->getfromPatientnr($patnr);
			if(count($test) > 0){
				$test = $test[0];

				$test->setVoornaam($voornaam);
				$test->setAchternaam($achternaam);
				$test->setGeslacht($geslacht);
				$test->setGeboortedatum($geboortedatum);

				if($message->getFieldvalue('PV1',7) != ''){
					list($dokterognummer,$dokternaam,$doktervoornaam, $null,$null, $dokterprefix) = explode($message->getFieldseperator2(),$message->getFieldvalue('PV1',7));
					$test->setDokterognummer($dokterognummer);
					$test->setDokternaam($dokterprefix . ' ' . $dokternaam . ' ' . $doktervoornaam);
				}


				try {
					$model->save($test);
				}
				catch(Exception $e) {
					$this->errorMessage($message, 'could not save: ' . $e->getMessage() );
					return false;
				}
			}

			// We made it!
			$this->finishMessage($message);

	}

	protected function processA45Message(hl7Object $message){
		require(FRAMEWORK . DS . 'conf' . DS . 'keukenpakket.php');

		$naam = $message->getFieldvalue('PID',5);
		list($achternaam,$voornaam) = explode($message->getFieldseperator2(),$naam);
		$dossnr = $message->getFieldvalue('PV1',19);
		list($patnr,$null,$null,$facility) = explode($message->getFieldseperator2(),$message->getFieldvalue('PID',3));
		list($unit, $kamer,$bed,$campus) = explode($message->getFieldseperator2(),$message->getFieldvalue('PV1',3));
		$geslacht = $message->getFieldvalue('PID',8);
		$geboortedatum = $this->converttotime($message->getFieldvalue('PID',7));

		if($message->getFieldvalue('PV1',3) == ''){
			//Ambulant
			$this->finishMessage($message);
			return 0;
		}

		if($kamer == 0  || $campus == 0 || $bed == 0) {
			//	Erh?? Zonder bed of campus kan ik niets doen!
			$this->errorMessage($message, 'geen kamer/bed/campus');
		}
		else {
			echo 'verhangen dossier ' . $naam . ' (' . $dossnr . ') VE: ' . $unit . ' Kamer: '. $kamer . ' Bed: ' . $bed . ' Campus: ' . $campus . '<br />';

			$patientObject = new keukenpatientObject();
			$patientObject->setVoornaam($voornaam);
			$patientObject->setAchternaam($achternaam);
			$patientObject->setCurrentdossiernr($dossnr);
			$patientObject->setPatientnr($patnr);
			$patientObject->setGeslacht($geslacht);
			$patientObject->setGeboortedatum($geboortedatum);
			$patientObject->setKamer($kamer);
			$patientObject->setBed($bed);
			$patientObject->setCampus($campus);
			$patientObject->setVe($unit);
			$patientObject->setLastmessageid($message->getFieldvalue('MSH',10));
			$patientObject->setLasteventtime($this->converttotime($message->getFieldvalue('EVN',6)));
			$patientObject->setLastmessagetime($this->converttotime($message->getFieldvalue('EVN',2)));

			if($message->getFieldvalue('PV1',7) != ''){
				list($dokterognummer,$dokternaam,$doktervoornaam, $null,$null, $dokterprefix) = explode($message->getFieldseperator2(),$message->getFieldvalue('PV1',7));
				$patientObject->setDokterognummer($dokterognummer);
				$patientObject->setDokternaam($dokterprefix . ' ' . $dokternaam . ' ' . $doktervoornaam);
			}

			$model = new keukenpatientModel();
			$test = $model->getfromPatientnr($patnr);
			if(count($test) > 0){
				$patientObject->setId($test[0]->getId());
			}

			try {
				$model->save($patientObject);
			}
			catch(Exception $e) {
				$this->errorMessage($message, 'could not save: ' . $e->getMessage() );
				return false;
			}

			// verwijderen bij de vorige patiënt?
			$oldpatnr = $message->getFieldvalue('MRG',1);
			$olddosnr = $message->getFieldvalue('MRG',5);
			$testpat = $model->get(array('AND' => array(
														array('patientnr' => array('mode' => '=', 'value' => $oldpatnr)),
														array('currentdossiernr' => array('mode' => '=', 'value' => $olddosnr))
													)));
			if(count($testpat) == 1){
				$testpat = $testpat[0];

				$testpat->setKamer('');
				$testpat->setBed('');
				$testpat->setCampus('');
				$testpat->setVe('');
				$testpat->setCurrentdossiernr('');

				$testpat->setDokterognummer('');
				$testpat->setDokternaam('');

				try{
					$model->save($testpat);
				}
				catch(Exception $e){
					$this->errorMessage($message, 'could not save: ' . $e->getMessage() );
					return false;
				}
			}

//			// register it
//			$this->registermovement($message);

			// We made it!
			$this->finishMessage($message);
		}
	}

	protected function ignoreMessage(hl7object $message, $reason){
		require(FRAMEWORK . DS . 'conf' . DS . 'keukenpakket.php');
echo '<pre>' . print_r($message,true) . '</pre>';
echo $reason;
		if( !rename($message->getSourcepath(),str_replace($hl7dir,$hl7ignoredir,$message->getSourcepath()))){
			echo 'error moving';
			return false;
		}
	}

	protected function errorMessage(hl7object $message, $reason){
		require(FRAMEWORK . DS . 'conf' . DS . 'keukenpakket.php');

		echo '<pre>' . print_r($message,true) . '</pre>';
		echo $message->getFieldvalue('PV1',3) . ' -> ' . $message->getSourcepath() . ' ' . $reason . '<br />';

		if( !rename($message->getSourcepath(),str_replace($hl7dir,$hl7errordir,$message->getSourcepath()))){
			echo 'error moving';
			return false;
		}

		$errmodel = new hl7errorModel();
		$errobject = new hl7errorObject();

		$errobject->setFile($message->getSourcepath());
		$errobject->setError($reason);

		print_r($errobject);

		try{
			$errmodel->save($errobject);
		}
		catch( Exception $e){
			echo $e->getMessage();
			return false;
		}
	}

	protected function finishMessage(hl7Object $message ){
		require(FRAMEWORK . DS . 'conf' . DS . 'keukenpakket.php');

		if( !rename($message->getSourcepath(),str_replace($hl7dir,$hl7donedir,$message->getSourcepath()))){
			echo 'error moving';
			return false;
		}
	}
}
?>