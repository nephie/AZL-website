<?php
class keukenpakketprsparser {
	public function processprs(){
		$prsmodel = new prspatientModel();
		$keukenmodel = new keukenpatientModel();
		
		try {
		
			//	All patiŽnts in PRS
			$all = $prsmodel->get();
			$allpatnr = array();
			echo '<pre>' . count($all) . '</pre>';
			foreach ($all as $prspatient){
				
				$allpatnr[$prspatient->getNr_pat()] = $prspatient->getNr_pat();
				
				$keukenpatiŽnt = $keukenmodel->getfromPatientnr($prspatient->getNr_pat());
				
				if(count($keukenpatiŽnt) == 1){
					$keukenpatiŽnt = $keukenpatiŽnt[0];
									
					if($keukenpatiŽnt->getCurrentdossiernr() != $prspatient->getNr_dos()){
						$keukenpatiŽnt->setCurrentdossiernr($prspatient->getNr_dos());
					
						$keukenmodel->save($keukenpatiŽnt);
					}
					
					if($keukenpatiŽnt->getDokterognummer() != $prspatient->getNr_dok()){
						$keukenpatiŽnt->setDokterognummer($prspatient->getNr_dok());
						$keukenpatiŽnt->setDokternaam('Dr. ' . $prspatient->getFnaam_dok() . ' ' . $prspatient->getVnaam_dok());
					
						$keukenmodel->save($keukenpatiŽnt);					
					}
					
					if(
						($keukenpatiŽnt->getAchternaam() != $prspatient->getFnaam_pat()) || 
						($keukenpatiŽnt->getVoornaam() != $prspatient->getVnaam_pat()) ||
						($keukenpatiŽnt->getGeslacht() != $prspatient->getGeslacht()) ||
						($keukenpatiŽnt->getGeboortedatum() != $prspatient->getDat_geb())
					){
						$keukenpatiŽnt->setAchternaam($prspatient->getFnaam_pat());
						$keukenpatiŽnt->setVoornaam($prspatient->getVnaam_pat());							
						$keukenpatiŽnt->setGeslacht($prspatient->getGeslacht());
						
						list($date, $time) = explode(' ',$prspatient->getDat_geb());
						list($year,$month,$day) = explode('-',$date);
						list($hour,$minute,$second) = explode(':',$time);				
						$gebdat = mktime($hour,$minute,$second,$month,$day,$year);				
						$keukenpatiŽnt->setGeboortedatum($gebdat);
						
						$keukenmodel->save($keukenpatiŽnt);
					}
					
					if(
						($keukenpatiŽnt->getKamer() != $prspatient->getNr_kamer()) || 
						($keukenpatiŽnt->getBed() != $prspatient->getNr_loc_bed()) ||
						($keukenpatiŽnt->getVerplaatsing() != $prspatient->getVerplaatsing())
					){
						$keukenpatiŽnt->setVe($prspatient->getNr_ve());
						$keukenpatiŽnt->setKamer($prspatient->getNr_kamer());
						$keukenpatiŽnt->setBed($prspatient->getNr_loc_bed());
						$keukenpatiŽnt->setVerplaatsing($prspatient->getVerplaatsing());

						$keukenmodel->save($keukenpatiŽnt);					
					}
				}
				elseif(count($keukenpatiŽnt) == 0){
					$keukenpatiŽnt = new keukenpatientObject();
					
					$keukenpatiŽnt->setAchternaam($prspatient->getFnaam_pat());
					$keukenpatiŽnt->setVoornaam($prspatient->getVnaam_pat());							
					$keukenpatiŽnt->setGeslacht($prspatient->getGeslacht());
					
					list($date, $time) = explode(' ',$prspatient->getDat_geb());
					list($year,$month,$day) = explode('-',$date);
					list($hour,$minute,$second) = explode(':',$time);				
					$gebdat = mktime($hour,$minute,$second,$month,$day,$year);				
					$keukenpatiŽnt->setGeboortedatum($gebdat);
					
					$keukenpatiŽnt->setPatientnr($prspatient->getNr_pat());
					$keukenpatiŽnt->setCurrentdossiernr($prspatient->getNr_dos());
					
					$keukenpatiŽnt->setCampus(999);
					$keukenpatiŽnt->setVe($prspatient->getNr_ve());
					$keukenpatiŽnt->setKamer($prspatient->getNr_kamer());				
					$keukenpatiŽnt->setBed($prspatient->getNr_loc_bed());
					$keukenpatiŽnt->setVerplaatsing($prspatient->getVerplaatsing());				
					
					$keukenpatiŽnt->setDokterognummer($prspatient->getNr_dok());				
					$keukenpatiŽnt->setDokternaam('Dr. ' . $prspatient->getFnaam_dok() . ' ' . $prspatient->getVnaam_dok());
													
					$keukenmodel->save($keukenpatiŽnt);
						
				}
				else {
					//TODO: Mail error
				}
			}
			echo '<pre>' . print_r($allpatnr, true) . '</pre>';
			//	Clean out patiŽnts not in the prs
			$keukenmodel->delete(array('patientnr' => array('mode' => 'NOT IN', 'value' => $allpatnr)));
		
		}
		catch(Exception $e)	{
			echo '<pre>EXCEPTION: ' . print_r($e, true) . '</pre>';
		}
			
		}
	
}
?>