<?php
class keukenpakketprsparser {
	public function processprs(){
		$prsmodel = new prspatientModel();
		$keukenmodel = new keukenpatientModel();
		
		try {
		
			//	All patiënts in PRS
			$all = $prsmodel->get();
			$allpatnr = array();
			echo '<pre>' . count($all) . '</pre>';
			foreach ($all as $prspatient){
				
				$allpatnr[$prspatient->getNr_pat()] = $prspatient->getNr_pat();
				
				$keukenpatiënt = $keukenmodel->getfromPatientnr($prspatient->getNr_pat());
				
				if(count($keukenpatiënt) == 1){
					$keukenpatiënt = $keukenpatiënt[0];
									
					if($keukenpatiënt->getCurrentdossiernr() != $prspatient->getNr_dos()){
						$keukenpatiënt->setCurrentdossiernr($prspatient->getNr_dos());
					
						$keukenmodel->save($keukenpatiënt);
					}
					
					if($keukenpatiënt->getDokterognummer() != $prspatient->getNr_dok()){
						$keukenpatiënt->setDokterognummer($prspatient->getNr_dok());
						$keukenpatiënt->setDokternaam('Dr. ' . $prspatient->getFnaam_dok() . ' ' . $prspatient->getVnaam_dok());
					
						$keukenmodel->save($keukenpatiënt);					
					}
					
					if(
						($keukenpatiënt->getAchternaam() != $prspatient->getFnaam_pat()) || 
						($keukenpatiënt->getVoornaam() != $prspatient->getVnaam_pat()) ||
						($keukenpatiënt->getGeslacht() != $prspatient->getGeslacht()) ||
						($keukenpatiënt->getGeboortedatum() != $prspatient->getDat_geb())
					){
						$keukenpatiënt->setAchternaam($prspatient->getFnaam_pat());
						$keukenpatiënt->setVoornaam($prspatient->getVnaam_pat());							
						$keukenpatiënt->setGeslacht($prspatient->getGeslacht());
						
						list($date, $time) = explode(' ',$prspatient->getDat_geb());
						list($year,$month,$day) = explode('-',$date);
						list($hour,$minute,$second) = explode(':',$time);				
						$gebdat = mktime($hour,$minute,$second,$month,$day,$year);				
						$keukenpatiënt->setGeboortedatum($gebdat);
						
						$keukenmodel->save($keukenpatiënt);
					}
					
					if(
						($keukenpatiënt->getKamer() != $prspatient->getNr_kamer()) || 
						($keukenpatiënt->getBed() != $prspatient->getNr_loc_bed()) ||
						($keukenpatiënt->getVerplaatsing() != $prspatient->getVerplaatsing())
					){
						$keukenpatiënt->setVe($prspatient->getNr_ve());
						$keukenpatiënt->setKamer($prspatient->getNr_kamer());
						$keukenpatiënt->setBed($prspatient->getNr_loc_bed());
						$keukenpatiënt->setVerplaatsing($prspatient->getVerplaatsing());

						$keukenmodel->save($keukenpatiënt);					
					}
				}
				elseif(count($keukenpatiënt) == 0){
					$keukenpatiënt = new keukenpatientObject();
					
					$keukenpatiënt->setAchternaam($prspatient->getFnaam_pat());
					$keukenpatiënt->setVoornaam($prspatient->getVnaam_pat());							
					$keukenpatiënt->setGeslacht($prspatient->getGeslacht());
					
					list($date, $time) = explode(' ',$prspatient->getDat_geb());
					list($year,$month,$day) = explode('-',$date);
					list($hour,$minute,$second) = explode(':',$time);				
					$gebdat = mktime($hour,$minute,$second,$month,$day,$year);				
					$keukenpatiënt->setGeboortedatum($gebdat);
					
					$keukenpatiënt->setPatientnr($prspatient->getNr_pat());
					$keukenpatiënt->setCurrentdossiernr($prspatient->getNr_dos());
					
					$keukenpatiënt->setCampus(999);
					$keukenpatiënt->setVe($prspatient->getNr_ve());
					$keukenpatiënt->setKamer($prspatient->getNr_kamer());				
					$keukenpatiënt->setBed($prspatient->getNr_loc_bed());
					$keukenpatiënt->setVerplaatsing($prspatient->getVerplaatsing());				
					
					$keukenpatiënt->setDokterognummer($prspatient->getNr_dok());				
					$keukenpatiënt->setDokternaam('Dr. ' . $prspatient->getFnaam_dok() . ' ' . $prspatient->getVnaam_dok());
													
					$keukenmodel->save($keukenpatiënt);
						
				}
				else {
					//TODO: Mail error
				}
			}
			echo '<pre>' . print_r($allpatnr, true) . '</pre>';
			//	Clean out patiënts not in the prs
			$keukenmodel->delete(array('patientnr' => array('mode' => 'NOT IN', 'value' => $allpatnr)));
		
		}
		catch(Exception $e)	{
			echo '<pre>EXCEPTION: ' . print_r($e, true) . '</pre>';
		}
			
		}
	
}
?>