<?php
class keukenpakketController extends controller {
	public function showstatus($parameters = array()){
		$view = new ui($this);

		$dienstmodel = new keukendienstModel();
		$kamermodel = new keukenkamerModel();

		$diensten = $dienstmodel->get();
		$adiensten = array();

		$kamercond = array();
		foreach($diensten as $dienst){
			if(myacl::isAllowed(myauth::getCurrentuser(),$dienst,'view')){
				$adiensten[$dienst->getId()] = $dienst;
			}
		}

		if(count($adiensten > 1)){

			$form = new form($parameters);
			$select = new selectField('dienst','Dienst',array('required'),true);

			$select->addOption(new selectoptionField('Alles','_all_',true));

			foreach($adiensten as $id => $dienst){
				$select->addOption(new selectoptionField($dienst->getName(),$id));
			}
			$form->addField($select);

			$continue = true;

			if($form->validate()){
				$all = false;
				foreach($form->getFieldvalue('dienst') as $id){
					$chosendienst[$id] = $adiensten[$id];
					if ($id == '_all_'){
						$all =true;
						break;
					}
				}
				if(!$all){
					$adiensten = $chosendienst;
				}
				$view->assign('form',$form);
			}
			elseif(!$form->isSent()){
				$view->assign('form',$form);
			}
			else {
				$continue = false;
			}
		}

		if($continue){
			foreach($adiensten as $id => $dienst){
				$kamers =  $kamermodel->getfromDienstid($id);

				foreach($kamers as $kamer){
					$kamercond[] = array('kamer' => array('mode' => '=','value' => $kamer->getKamernr()));
				}
			}

			if(count($kamercond) > 0){
				$kamercond = array('OR' => $kamercond);
			}
			else {
				//Geen kamers, lege lijst voorzien
				$kamercond = array('kamer' => array('mode' => '=','value' => '-1'));
			}

			$model = new keukenpatientModel();

			$count = $model->getcount($kamercond);
			$view->assign('count',$count);

			$grid = new mygrid('keukenstatus');
			$grid->setModel($model);
			$grid->setDefaultconditions($kamercond);
			$grid->setDefaultorder(array('fields' => array('kamer','bed'), 'type' => 'ASC'));
			$grid->registerEditrequest('keukenpakket','editPatient',array('title' => 'Maaltijdfiche aanpassen','id' => '{id}'));
			$grid->setPagesize(999);

			$view->assign('grid',$grid);



			$this->response->assign($this->self,'innerHTML',$view->fetch('keukenpakket_showstatus.tpl'));
		}
	}
/*
	public function showmovement($parameters = array()){
		$type = $parameters['Type'];
		$view = new ui($this);

			$dienstmodel = new keukendienstModel();
		$kamermodel = new keukenkamerModel();

		$diensten = $dienstmodel->get();
		$adiensten = array();

		$kamercond = array();
		foreach($diensten as $dienst){
			if(myacl::isAllowed(myauth::getCurrentuser(),$dienst,'view')){
				$adiensten[$dienst->getId()] = $dienst;
			}
		}

		if(count($adiensten > 1)){

			$form = new form($parameters);
			$select = new selectField('dienst','Dienst',array('required'),true);

			$select->addOption(new selectoptionField('Alles','_all_',true));

			foreach($adiensten as $id => $dienst){
				$select->addOption(new selectoptionField($dienst->getName(),$id));
			}
			$form->addField($select);
			$form->addField(new hiddenField('Type',$type));

			$continue = true;

			if($form->validate()){
				$all = false;
				foreach($form->getFieldvalue('dienst') as $id){
					$chosendienst[$id] = $adiensten[$id];
					if ($id == '_all_'){
						$all =true;
						break;
					}
				}
				if(!$all){
					$adiensten = $chosendienst;
				}
				$view->assign('form',$form);
			}
			elseif(!$form->isSent()){
				$view->assign('form',$form);
			}
			else {
				$continue = false;
			}
		}

		if($continue){
			foreach($adiensten as $id => $dienst){
				$kamers =  $kamermodel->getfromDienstid($id);

				foreach($kamers as $kamer){
					$kamercond[] = array('kamer' => array('mode' => '=','value' => $kamer->getKamernr()));
				}
			}

			if(count($kamercond) > 0){
				$kamercond = array('OR' => $kamercond);
			}
			else {
				//Geen kamers, lege lijst voorzien
				$kamercond = array('kamer' => array('mode' => '=','value' => '-1'));
			}


			$tcond = array();
			if($type == 'opname'){
				$view->assign('title', 'Opnames');
				$tcond = array('type' => array('mode' => 'IN', 'value' => array('A01','A13','A45')));
			}
			elseif ($type == 'verplaatsing'){
				$view->assign('title','Verplaatsingen');
				$tcond = array('type' => array('mode' => 'IN', 'value' => array('A02','A12')));
			}
			elseif($type == 'ontslag'){
				$view->assign('title','Ontslagen');
				$tcond = array('type' => array('mode' => 'IN', 'value' => array('A03','A40')));
			}

			$cond = array('AND' => array($kamercond,$tcond));

			$grid = new mygrid('hl7movement_' . $type);
			$grid->setModel(new hl7movementModel());
			if(count($cond) > 0){
				$grid->setDefaultconditions($cond);
			}
			$grid->setPagesize(999);
			$grid->setDefaultorder(array('fields' => array('time'),'type' => 'DESC'));

			$view->assign('grid',$grid);

			$view->assign('type',$type);

			$this->response->assign($this->self,'innerHTML',$view->fetch('keukenpakket_showmovement.tpl'));
		}
	}
*/
	protected function buildform($parameters = array()){
		$form =  new mygridform($parameters,$parameters['-gridid-'],'edit','keukenpakket','editPatient');
		$form->setPhasedrequest(new ajaxrequest('keukenpakket','updateform'));

		$form->addField(new hiddenField('id',$parameters['id']));
		$form->addField(new hiddenField('title',$parameters['title']));

		$patientmodel = new keukenpatientModel();
		$flash = new popupController();

		$patients = $patientmodel->getfromId($parameters['id']);

		if(count($patients) == 1 ){
			$patient = $patients[0];

			$dieetselect = new selectField('dieet','Dieet',array('required'),true);
			$form->addField($dieetselect);

			$drankselect = new selectField('drank','Drank',array('required'));
			$form->addField($drankselect);
		}
		else {
			$flash->createflash(array('name' => 'err','type' => 'error', 'content' => 'Deze patiënt werd niet teruggevonden!'));
		}
		return $form;
	}

	public function updateform($parameters = array()){
		$form = $this->buildform($parameters);

		$view = new ui($this);
		$view->assign('form' , $form);
		$this->response->assign('keukenpakket_form','innerHTML', $view->fetch('keukenpakket_form.tpl'));
	}

	public function editPatient($parameters = array()){
		$patientmodel = new keukenpatientModel();
		$flash = new popupController();
		$view = new ui($this);

		$patients = $patientmodel->getfromId($parameters['id']);

		if(count($patients) == 1 ){
			$patient = $patients[0];
			$view->assign('patient',$patient);

			$form = $this->buildform($parameters);
			$form->setNofocus(false);

			if($form->validate()){
				if($form->confirmed($this, 'Bent u zeker?')){

				}
				else {

				}
			}
			elseif(!$form->isSent())  {
				$view->assign('form' , $form);
				return $view->fetch('keukenpakket_editpatient.tpl');
			}
			else {
				$this->response->assign('formerror_' . $form->getId(),'innerHTML', 'Gelieve alle benodigde velden correct in te vullen.', true);
				return false;
			}
		}
		else {
			$flash->createflash(array('name' => 'err','type' => 'error', 'content' => 'Deze patiënt werd niet teruggevonden!'));
			return false;
		}
	}
}
?>