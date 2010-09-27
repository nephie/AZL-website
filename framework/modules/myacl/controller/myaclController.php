<?php
class myaclController extends controller {

	public function listacl($parameters = array()){
		$target = (isset($parameters['targetoutput']))? $parameters['targetoutput']: $this->self;

		$view = new ui($this);



		$conds = array();
		$add = false;
		$showobject = true;
		$showrequester = true;
		if(isset($parameters['objecttype'])){
			$conds[] = array('objecttype' => array('mode' => '=','value' => $parameters['objecttype']));
			$conds[] = array('objectid' => array('mode' => '=','value' => $parameters['objectid']));
			$add = true;
			$showobject = false;
			$gridid = 'listacl_o_' . $parameters['objecttype'] . '_' . $parameters['objectid'];
		}
		$view->assign('showobject',$showobject);

		if(isset($parameters['requestertype'])){
			$conds[] = array('requestertype' => array('mode' => '=','value' => $parameters['requestertype']));
			$conds[] = array('requesterid' => array('mode' => '=','value' => $parameters['requesterid']));
			$showrequester = false;
			$gridid = 'listacl_r_' . $parameters['requestertype'] . '_' . $parameters['requesterid'];
		}
		$view->assign('showrequester',$showrequester);

		$grid = new mygrid($gridid);
		$grid->setModel(new processedmyaclModel());

		$grid->setDefaultconditions(array('AND' => $conds));
		$grid->setDefaultorder(array('fields' => array('requester','rightdesc'),'type' => 'ASC'));

		if($add){
			$object = new $parameters['objecttype']();
			$object->setId($parameters['objectid']);
			if(myacl::isAllowed(myauth::getCurrentuser(),$object,'managerights')){
				$grid->registerAddrequest('myacl','addacl',array_merge(array('title' => 'Recht toevoegen'),$parameters));
				$grid->registerDeleterequest('myacl','deleteacl',array('title' => 'Recht verwijderen','id' => '{id}'));
			}
		}

		$view->assign('acllist',$grid);

		if($target == '_return_'){
			return $view->fetch('myacl_listacl.tpl');
		}
		else {
			$this->response->assign($target,'innerHTML',$view->fetch('myacl_listacl.tpl'));
		}
	}

	public function getRequesterlist($search,$extraparams){
		include(FRAMEWORK . DS . 'conf' . DS . 'myacl.php');
		$type = $extraparams['objectype'];

		$finalresult = array();
		if(isset($myacl[$type])){
			$requesters = $myacl[$type]['requesters'];

			foreach($requesters as $requester => $reqconf){
				$function = $reqconf['searchfunction'];

				$modelname = str_replace('Object','Model',$requester);
				$model = new $modelname();

				$result = $model->$function('*' . $search . '*');

				$rc = new ReflectionClass($requester);
				if($rc->hasMethod('getMyacldisplayfield')){
					$dispfunc = 'getMyacldisplayfield';
				}
				else {
					$dispfunc = 'getId';
				}

				foreach ($result as $row){
					$finalresult[$row->$dispfunc()] = $row->$dispfunc();
				}
			}
		}

		return $finalresult;
	}

	public function addacl($parameters){
		include(FRAMEWORK . DS . 'conf' . DS . 'myacl.php');
		$view = new ui($this);

		$form = new mygridform($parameters,$parameters['-gridid-'],'edit');

		$objecttype = $parameters['objecttype'];
		$objectid = $parameters['objectid'];

		$form->addField(new suggestselectField('myacl','getRequesterlist' ,'requester','Aanvrager','',array('required'), array('objectype' => ($objecttype != 'securitytarget') ? $objecttype : $objectid)));

		if($objecttype == 'securitytarget'){
			$rights = $myacl[$objectid]['rights'];
		}
		else {
			$rights = $myacl[$objecttype]['rights'];
		}

		$object = new $objecttype();
		$object->setId($objectid);

		if(myacl::isAllowed(myauth::getCurrentuser(),$object,'_ALL_')){
			$form->addField(new checkboxField('form_all','Full control','_ALL_',false));
			$form->addField(new checkboxField('form_managerights','Rechten beheren','managerights',false));
		}

		foreach($rights as $right => $rightconf){
			$form->addField(new checkboxField('form_' . $right,$rightconf['description'],$right,false));
		}

		$form->addField(new hiddenField('objecttype',$objecttype));
		$form->addField(new hiddenField('objectid',$objectid));
		$form->addField(new hiddenField('module',$parameters['module']));

		if($form->validate()){

			$requestertypes = $myacl[($objecttype != 'securitytarget') ? $objecttype : $objectid]['requesters'];
			$requesters = array();

			foreach($form->getFieldvalue('requester') as $requestername){
				foreach($requestertypes as $type => $conf){
					$modelname = str_replace('Object','Model',$type);
					$model = new $modelname();

					$func = $conf['getfunction'];

					$res = $model->$func($requestername);
					if(count($res) == 1){
						$requesters[] = $res[0];

						break;
					}
				}
			}

			$selectedrights = array();

			if($form->getFieldvalue('form_all') == '_ALL_'){
				$selectedrights['_ALL_'] = '_ALL_';
			}
			else {

				if($form->getFieldvalue('form_managerights') == 'managerights'){
					$selectedrights['managerights'] = 'managerights';
				}

				foreach($rights as $right => $rightconf){
					if($form->getFieldvalue('form_' . $right) == $right){
						$selectedrights[$right] = $right;

						if(isset($rightconf['requires'])){
							foreach($rightconf['requires'] as $required){
								$selectedrights[$required] = $required;
							}
						}
					}
				}
			}

			try{

				$object = new $objecttype();
				$object->setId($objectid);

				foreach($requesters as $requester){
					foreach($selectedrights as $selectedright){
						myacl::setAcl($requester,$object,$selectedright,1);
					}
				}
			}
			catch (Exception $e){
				$flash = new popupController();
				$flash->createflash(array('name' => 'err', 'type' => 'error', 'content' => 'De gegevens zijn niet goed doorgevoerd! Raadpleeg de informaticadienst.'));

				return false;
			}

			$this->response->assign('gridextra_' . $parameters['-gridid-'], 'innerHTML','');

			$flash = new popupController();
			$flash->createflash(array('name' => 's', 'type' => 'success', 'content' => 'De gegevens zijn goed doorgevoerd.'));

			return true;
		}
		elseif(!$form->isSent()){
			$view->assign('form',$form);

			return $view->fetch('myacl_addacl.tpl');
		}
	}

	public function deleteacl($parameters){
		$view = new ui($this);

		$model = new processedmyaclModel();

		$acl = $model->getfromId($parameters['id']);
		$dependant = array();
		if(count($acl) == 1){
			$acl = $acl[0];


			include(FRAMEWORK . DS . 'conf' . DS . 'myacl.php');

			$type = $acl->getObjecttype();

			if($type == 'securitytarget'){
				$type = $acl->getObjectid();
			}

			$aclconf = $myacl[$type];
			$dependant = array();
			foreach($aclconf['rights'] as $right => $rightconf){
				if(in_array($acl->getRight(),$rightconf['requires'])){
					$otypecond = array('objecttype' => array('mode' => '=', 'value' => $acl->getObjecttype()));
					$oidcond = array('objectid' => array('mode' => '=', 'value' => $acl->getObjectid()));
					$rtypecond = array('requestertype' => array('mode' => '=', 'value' => $acl->getRequestertype()));
					$ridcond = array('requesterid' => array('mode' => '=', 'value' => $acl->getRequesterid()));

					$righcond = array('right' => array('mode' => '=', 'value' => $right));

					$cond = array('AND' => array($otypecond,$oidcond,$ridcond,$rtypecond,$righcond));

					$dependant = array_merge($dependant,$model->get($cond));
				}
			}
		}

		if($parameters['sure'] == 'sure'){
			$flash = new popupController();

			$ids[] = $parameters['id'];
			foreach($dependant as $depacl){
				$ids[] = $depacl->getId();
			}

			$cond = array('id' => array('mode' => 'IN', 'value' => $ids));

			try{
				$model->delete($cond);
			}
			catch(Exception $e){
				$flash->createflash(array('name' => 'error','type' => 'error','content' => 'De gegevens zijn niet goed verwijderd! Raadpleeg de informaticadienst.'));
				return false;
			}

			$flash->createflash(array('name' => 'success','type' => 'success','content' => 'De gegevens zijn goed verwijderd.'));
			return true;
		}
		else {

			$view->assign('acl',$acl);
			$view->assign('dependant',$dependant);
			return $view->fetch('myacl_deleteacl.tpl');

		}
	}
}
?>