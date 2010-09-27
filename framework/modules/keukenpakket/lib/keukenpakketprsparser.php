<?php
class keukenpakketprsparser {
	public function processprs(){
		$prsmodel = new prspatientModel();
		$keukenmodel = new keukenpatientModel();
		
		try {
		
			//	All pati�nts in PRS
			$all = $prsmodel->get();
			$allpatnr = array();
			echo '<pre>' . count($all) . '</pre>';
			foreach ($all as $prspatient){
				
				$allpatnr[$prspatient->getNr_pat()] = $prspatient->getNr_pat();
				
				$keukenpati�nt = $keukenmodel->getfromPatientnr($prspatient->getNr_pat());
				
				if(count($keukenpati�nt) == 1){
					$keukenpati�nt = $keukenpati�nt[0];
									
					if($keukenpati�nt->getCurrentdossiernr() != $prspatient->getNr_dos()){
						$keukenpati�nt->setCurrentdossiernr($prspatient->getNr_dos());
					
						$keukenmodel->save($keukenpati�nt);
					}
					
					if($keukenpati�nt->getDokterognummer() != $prspatient->getNr_dok()){
						$keukenpati�nt->setDokterognummer($prspatient->getNr_dok());
						$keukenpati�nt->setDokternaam('Dr. ' . $prspatient->getFnaam_dok() . ' ' . $prspatient->getVnaam_dok());
					
						$keukenmodel->save($keukenpati�nt);					
					}
					
					if(
						($keukenpati�nt->getAchternaam() != $prspatient->getFnaam_pat()) || 
						($keukenpati�nt->getVoornaam() != $prspatient->getVnaam_pat()) ||
						($keukenpati�nt->getGeslacht() != $prspatient->getGeslacht()) ||
						($keukenpati�nt->getGeboortedatum() != $prspatient->getDat_geb())
					){
						$keukenpati�nt->setAchternaam($prspatient->getFnaam_pat());
						$keukenpati�nt->setVoornaam($prspatient->getVnaam_pat());							
						$keukenpati�nt->setGeslacht($prspatient->getGeslacht());
						
						list($date, $time) = explode(' ',$prspatient->getDat_geb());
						list($year,$month,$day) = explode('-',$date);
						list($hour,$minute,$second) = explode(':',$time);				
						$gebdat = mktime($hour,$minute,$second,$month,$day,$year);				
						$keukenpati�nt->setGeboortedatum($gebdat);
						
						$keukenmodel->save($keukenpati�nt);
					}
					
					if(
						($keukenpati�nt->getKamer() != $prspatient->getNr_kamer()) || 
						($keukenpati�nt->getBed() != $prspatient->getNr_loc_bed()) ||
						($keukenpati�nt->getVerplaatsing() != $prspatient->getVerplaatsing())
					){
						$keukenpati�nt->setVe($prspatient->getNr_ve());
						$keukenpati�nt->setKamer($prspatient->getNr_kamer());
						$keukenpati�nt->setBed($prspatient->getNr_loc_bed());
						$keukenpati�nt->setVerplaatsing($prspatient->getVerplaatsing());

						$keukenmodel->save($keukenpati�nt);					
					}
				}
				elseif(count($keukenpati�nt) == 0){
					$keukenpati�nt = new keukenpatientObject();
					
					$keukenpati�nt->setAchternaam($prspatient->getFnaam_pat());
					$keukenpati�nt->setVoornaam($prspatient->getVnaam_pat());							
					$keukenpati�nt->setGeslacht($prspatient->getGeslacht());
					
					list($date, $time) = explode(' ',$prspatient->getDat_geb());
					list($year,$month,$day) = explode('-',$date);
					list($hour,$minute,$second) = explode(':',$time);				
					$gebdat = mktime($hour,$minute,$second,$month,$day,$year);				
					$keukenpati�nt->setGeboortedatum($gebdat);
					
					$keukenpati�nt->setPatientnr($prspatient->getNr_pat());
					$keukenpati�nt->setCurrentdossiernr($prspatient->getNr_dos());
					
					$keukenpati�nt->setCampus(999);
					$keukenpati�nt->setVe($prspatient->getNr_ve());
					$keukenpati�nt->setKamer($prspatient->getNr_kamer());				
					$keukenpati�nt->setBed($prspatient->getNr_loc_bed());
					$keukenpati�nt->setVerplaatsing($prspatient->getVerplaatsing());				
					
					$keukenpati�nt->setDokterognummer($prspatient->getNr_dok());				
					$keukenpati�nt->setDokternaam('Dr. ' . $prspatient->getFnaam_dok() . ' ' . $prspatient->getVnaam_dok());
													
					$keukenmodel->save($keukenpati�nt);
						
				}
				else {
					//TODO: Mail error
				}
			}
			echo '<pre>' . print_r($allpatnr, true) . '</pre>';
			//	Clean out pati�nts not in the prs
			$keukenmodel->delete(array('patientnr' => array('mode' => 'NOT IN', 'value' => $allpatnr)));
		
		}
		catch(Exception $e)	{
			echo '<pre>EXCEPTION: ' . print_r($e, true) . '</pre>';
		}
			
		}
	
}
?>