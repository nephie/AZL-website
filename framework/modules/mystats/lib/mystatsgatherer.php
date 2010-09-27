<?php
class mystatsgatherer {

	public function gatherbedbez(){
		$dienstmodel = new keukendienstModel();
		//$kamermodel = new keukenkamerModel();
		$model = new keukenpatientModel();
		$statmodel = new bedbezModel();

		$diensten = $dienstmodel->get();
		$adiensten = array();

		$time = time();

		foreach($diensten as $dienst){
			$adiensten[$dienst->getId()] = $dienst;
		}

		$outputdiensten = array();
		$totalcond = array();
		$totalmax = 0;
		foreach($adiensten as $id => $dienst){
			//$kamercond = array();
			//$kamers =  $kamermodel->getfromDienstid($id);

//			foreach($kamers as $kamer){
//				$kamercond[] = array('kamer' => array('mode' => '=','value' => $kamer->getKamernr()));
//			}
//
//			$totalcond = array_merge($totalcond,$kamercond);
//			if(count($kamercond) > 0){
//				$kamercond = array('OR' => $kamercond);
//			}
//			else {
//				//Geen kamers, lege lijst voorzien
//				$kamercond = array('kamer' => array('mode' => '=','value' => '-1'));
//			}

			$cond = array('ve' => array('mode' => '=', 'value' => $dienst->getDienstnr()));

			$count = $model->getcount($cond);

			$statobject = new bedbezObject();

			$statobject->setTime($time);
			$statobject->setVe($dienst->getDienstnr());
			$statobject->setAantal($count);

			try{
				$statmodel->save($statobject);
			}
			catch(Exception $e){

			}
		}
	}
}
?>