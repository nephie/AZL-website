<?php

class mypageadminController extends controller {

public function removedwaypoint($key, $action,$parameters,$currenthaspoints){

		if($action == 'managepages'){
			$parameters['parentid'] = 0;

			$this->managepages($parameters);
		}
	}

	public function managepages($parameters = array()){
		$view = new ui($this);

		if(!isset($parameters['parentid'])){
			$parentid = 0;
		}
		else {
			$parentid = $parameters['parentid'];
		}

		$pagemodel = new pageModel();

		if($parentid != 0){
			$currentpage = $pagemodel->getfromId($parentid);
			if(count($currentpage) == 1){
				$currentpage = $currentpage[0];
				if(!$parameters['history']){
					$this->response->addWaypoint( 'mypageadmin', 'managepages', 'pageadmin', $parameters);
				}
			}
		}

		$grid = new mygrid('pagelist_' . $parentid);

		$grid->setModel($pagemodel);
		$grid->setDefaultconditions(array('parentid' => array('mode' => '=','value' => $parentid)));
		$grid->setDefaultorder(array('fields' => array('order'),'type' => 'ASC'));
		$grid->setOrderfield('order');

		$grid->registerRequest('title','mypageadmin','managepages',array('parentid' => '{id}'));

		if($currentpage instanceof pageObject){
			$add = myacl::isAllowed(myauth::getCurrentuser(),$currentpage,'addpage');
		}
		else {
			$add = myacl::isAllowed(myauth::getCurrentuser(),new securitytarget('pagemanagement'),'addrootpage');
		}

		if($add){
			$grid->registerAddrequest('mypageadmin','addpage',array('title' => 'Pagina toevoegen', 'parentid' => $parentid));
		}

		$view->assign('grid',$grid);

		$pageid = ($currentpage instanceof pageObject) ? $currentpage->getId() : -1;
		$areamodel = new areaModel();
		$areas = $areamodel->get();

		$modules = array();

		$idcond = array('pageid' => array('mode' => '=' , 'value' => $pageid));

		foreach($areas as $area){
			$areacond = array('areaid' => array('mode' => '=', 'value' => $area->getId()));

			$grid = new mygrid('modules_page_' . $pageid . '_area_' . $area->getId());
			$grid->setModel(new processedmodulepageModel());
			$grid->setDefaultconditions(array('AND' => array($idcond,$areacond)));
			$grid->setDefaultorder(array('fields' => array('order'), 'type' => 'ASC'));
			$grid->setOrderfield('order');

			$grid->registerAddrequest('mypageadmin','addmodulepagelink',array('title' => 'Module aan pagina toevoegen', 'areaid' => $area->getId() , 'pageid' => $pageid));

			$modules[$area->getName()] = $grid;
		}

		$view->assign('modules',$modules);

		if($currentpage instanceof pageObject){
			$aclcontroller = new myaclController();
			$acl = $aclcontroller->listacl(array('targetoutput' => '_return_','objecttype' => 'pageObject','objectid'=> $currentpage->getId()));
			$view->assign('acl',$acl);

			$titleform = new form($parameters);

			$titleform->addField(new textField('title','Titel',$currentpage->getTitle(),array('required')));
			$titleform->addField(new hiddenField('parentid',$currentpage->getId()));

			if($titleform->validate()){

				$flash = new popupController();
				if($titleform->getFieldvalue('title') != $currentpage->getTitle()){
					$currentpage->setTitle($titleform->getFieldvalue('title'));

					try {
						$pagemodel->save($currentpage);
					}
					catch(Exception $e ){
						$flash->createflash(array('name' => 'err','type' => 'error','content' => 'De gegevens zijn niet goed bewaard! Raadpleeg de informaticadienst.'));
						return false;
					}

					$flash->createflash(array('name' => 'success','type' => 'success','content' => 'De gegevens zijn goed bewaard.'));
				}
				else {
					$flash->createflash(array('name' => 'warning','type' => 'warning','content' => 'De nieuwe titel is gelijk aan de vorige titel.'));
				}

				$view->assign('titleform',$titleform);
			}
			elseif(!$titleform->isSent()){
				$view->assign('titleform',$titleform);
			}
			else {
				return false;
			}
		}

		$theparentid = $parentid;
		$path = array();

		while($theparentid != 0){
			$parent = $pagemodel->getfromId($theparentid);

			if(count($parent) == 1){
				$parent = $parent[0];
				$tmp = array();
				$tmp['page'] = $parent;
				$tmp['request'] = new ajaxrequest('mypageadmin','managepages',array('parentid' => $theparentid));
				$path[] = $tmp;
				$theparentid = $parent->getParentid();
			}
			else {
				$theparentid = 0;
			}
		}
		$rootpage = new pageObject();
		$rootpage->setTitle('Root');
		$root['page'] = $rootpage;
		$root['request'] = new ajaxrequest('mypageadmin','managepages',array('parentid' => 0));
		$path[] = $root;

		$view->assign('path',array_reverse($path));


		$view->assign('currentpage',$currentpage);
		$this->response->assign($this->self,'innerHTML',$view->fetch('mypageadmin_managepages.tpl'));
	}

	public function addpage($parameters = array()){
		$view = new ui($this);

		$form = new mygridform($parameters,$parameters['-gridid-'],'edit');

		$form->addField(new hiddenField('parentid',$parameters['parentid']));
		$form->addField(new textField('title','Titel','',array('required')));

		if($form->validate()){
			$pagemodel = new pageModel();

			$newpage = new pageObject();
			$newpage->setTemplate('page.tpl');
			$newpage->setParentid($parameters['parentid']);
			$newpage->setTitle($parameters['title']);
			$newpage->setOrder($pagemodel->getmax('order',array('parentid' => array('mode' => '=', 'value' => $parameters['parentid']))) + 1);

			$flash = new popupController();

			try{
				$pagemodel->save($newpage);
			}
			catch(Exception $e){
				$flash->createflash(array('name' => 'err','type' => 'error','content' => 'De gegevens zijn niet goed bewaard! Raadpleeg de informaticadienst.'));
				return false;
			}

			$flash->createflash(array('name' => 'success','type' => 'success','content' => 'De gegevens zijn goed bewaard.'));

			$this->response->assign('gridextra_' . $parameters['-gridid-'], 'innerHTML', '');

			return true;
		}
		elseif(!$form->isSent()){
			$view->assign('form',$form);

			return $view->fetch('mypageadmin_addpage.tpl');
		}


	}

	public function addmodulepagelink($parameters = array()){

		if(!isset($parameters['moduleid'])){
			$view = new ui($this);

			$grid = new mygrid('addmodules');
			$grid->setModel(new processedmoduleModel());
			$grid->registerRequest('title','mypageadmin','addmodulepagelink',array('title' => $parameters['title'], 'areaid' => $parameters['areaid'], 'pageid' => $parameters['pageid'],'moduleid' => '{id}', 'oldgrid' => $parameters['-gridid-']));

			$view->assign('grid', $grid);

			return $view->fetch('mypageadmin_addmodulepagelink.tpl');
		}
		else {
			$linkmodel = new modulepageModel();
			$link = new modulepageObject();

			$link->setPageid($parameters['pageid']);
			$link->setAreaid($parameters['areaid']);
			$link->setModuleid($parameters['moduleid']);
			$link->setOrder($linkmodel->getmax('order',array('AND' => array(array('areaid' => array('mode' => '=', 'value' => $parameters['areaid'])), array('pageid' => array('mode' => '=', 'value' => $parameters['pageid']))))) + 1);

			$flash = new popupController();

			try {
				$linkmodel->save($link);
			}
			catch(Exception $e){
				$flash->createflash(array('name' => 'error','type' => 'error', 'content' => 'De aanpassing werd niet doorgevoerd! Contacteer de informaticadienst.'));
				return false;
			}

			$flash->createflash(array('name' => 'error','type' => 'success', 'content' => 'De aanpassing werd goed doorgevoerd.'));

			$gridcontr = new mygridController();
			$gridcontr->reloadgrid($parameters['oldgrid']);
		}
	}

	public function deletemodulepagelink($parameters = array()){
		if($parameters['sure'] == 'sure'){
			$model = new myarticlesectionlinkModel();
			$flash = new popupController();

			$curlink = $model->getfromId($parameters['id']);
			if(count($curlink) == 1){


				try {
					$model->deletebyId($parameters['id']);
				}
				catch(Exception $e){
					$flash->createflash(array('name' => 'error','type' => 'error', 'content' => 'De aanpassing werd niet doorgevoerd! Contacteer de informaticadienst.'));
					return false;
				}
			}
			else {
				$flash->createflash(array('name' => 'warning','type' => 'warning', 'content' => 'De aanpassing werd niet doorgevoerd omdat deze link reeds verwijderd was!'));
				return false;
			}

			$flash->createflash(array('name' => 'error','type' => 'success', 'content' => 'De aanpassing werd goed doorgevoerd.'));
			return true;
		}
		else {
			$model = new processedmodulepageModel();
			$link = $model->getfromId($parameters['id']);
			if(count($link) == 1){
				$view = new ui($this);

				$view->assign('link',$link[0]);

				return $view->fetch('mypageadmin_deletemodulepagelink.tpl');
			}
		}
	}
}

?>