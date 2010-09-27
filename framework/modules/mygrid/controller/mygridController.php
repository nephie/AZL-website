<?php

class mygridController extends controller
{

	public function removedwaypoint($key, $action,$parameters,$currenthaspoints){

		if($action == 'jumppage'){
			$parameters['page'] = 1;

			$this->jumppage($parameters);
		}
		elseif($action == 'search'){
			$this->clearsearch($parameters);
		}
		elseif($action == 'editrequest' || $action == 'deleterequest'){
			$this->closeextra(array('history' => 'history', 'id' => 'gridextra_' . $parameters['-gridid-']));
		}
	}

	public function reloadgrid($id){
		$grid = new mygrid($id);
		$template = new ui($this);

		$template->assign('grid',$grid);
		$this->response->assign($id,'innerHTML', $template->fetch($parameters['viewprefix'] . 'mygridstructure.tpl'));
	}

	public function editrequest($parameters){
		$template = new ui($this);

		$grid = new mygrid($parameters['-gridid-']);

		$template->assign('grid',$grid);

		$controllername = $parameters['controller'] . 'Controller';
		$controller = new $controllername();
		$action = $parameters['action'];
		$result = $controller->$action($parameters);

		if($result === true){
			$this->reloadgrid($parameters['-gridid-']);
		}
		elseif($result !== false) {


			if(!$parameters['history']){
				$this->response->addWaypoint( 'mygrid', 'editrequest', $grid->getId(), $parameters);
			}

			if($parameters['type'] == 'popup'){
				$popup = new popupController();
				$popup->create(array('name' => $parameters['-gridid-'], 'title' => $parameters['title'], 'content' => $result ));
			}
			else {
				$template->assign('title',$parameters['title']);

				$closerequest = new ajaxrequest('mygrid', 'closeextra', array('id' => 'gridextra_' . $parameters['-gridid-']));
				$template->assign('closerequest', $closerequest);

				$template->assign('content',$result);

				$this->response->assign('gridextra_' . $parameters['-gridid-'],'innerHTML', $template->fetch($parameters['viewprefix'] . 'mygrid_editrequest.tpl'));
				$this->response->script('var myFx = new Fx.Scroll(window).toElement(\'gridextra_' . $parameters['-gridid-'] .'\')');
			}
		}
	}

	public function deleterequest($parameters){
		$template = new ui($this);

		$grid = new mygrid($parameters['-gridid-']);

		$controllername = $parameters['controller'] . 'Controller';
		$controller = new $controllername();
		$action = $parameters['action'];
		$result = $controller->$action($parameters);

		if($result === true){
			$this->reloadgrid($parameters['-gridid-']);
			$this->response->assign('gridextra_' . $parameters['-gridid-'],'innerHTML', '' );
		}
		elseif($result !== false) {
			$template->assign('title',$parameters['title']);

			$closerequest = new ajaxrequest('mygrid', 'closeextra', array('id' => 'gridextra_' . $parameters['-gridid-']));
			$template->assign('closerequest', $closerequest);

			$template->assign('content',$result);

			$ja = new ajaxrequest('mygrid', 'deleterequest' ,array_merge($parameters,array('sure' => 'sure')));
			$template->assign('ja' , $ja);

			if(!$parameters['history']){
				$this->response->addWaypoint( 'mygrid', 'deleterequest', $grid->getId(), array('-gridid-' => $grid->getId(), 'controller' => $parameters['controller'], 'action' => $parameters['action'], 'id' => $parameters['id'], 'title' => $parameters['title']));
			}
			$this->response->assign('gridextra_' . $parameters['-gridid-'],'innerHTML', $template->fetch($parameters['viewprefix'] . 'mygrid_deleterequest.tpl'));
			$this->response->script('var myFx = new Fx.Scroll(window).toElement(\'gridextra_' . $parameters['-gridid-'] .'\')');
		}
	}

	public function closeextra($parameters){
		$this->response->assign($parameters['id'],'innerHTML', '' );
		$gridid = str_replace('gridextra_','',$parameters['id']);
		$this->response->script('var myFx = new Fx.Scroll(window).toElement(\''. $gridid .'\')');
		if($parameters['history'] != 'history'){
				$this->response->addWaypoint( 'mygrid', 'closeextra', $gridid, array('id' => $parameters['id']));
		}
	}

	public function gotopage($parameters = array()){

		$form = new form($parameters);

		$form->addField(new hiddenField('gridid' , $parameters['gridid'])); // We need to rely on the gridid being sent correctly

		$grid = new mygrid($form->getFieldvalue('gridid'));

		$form->addField(new inlinetextField('page' , 'Go to page #', '' , array('required', 'numeric' , 'range:1<->' . $grid->getTotalpages())));


		$form->setSubmittext('Go to page');

		if($form->validate()){

			$grid->setPage($form->getFieldvalue('page'));

			$template = new ui($this);

			$template->assign('grid',$grid);

			$this->response->addWaypoint( 'mygrid', 'jumppage', $grid->getId(), array('gridid' => $grid->getId(), 'page' => $form->getFieldvalue('page')));
			$this->response->assign($form->getFieldvalue('gridid') , 'innerHTML', $template->fetch($parameters['viewprefix'] . 'mygridstructure.tpl'));
			$this->response->script('var myFx = new Fx.Scroll(window).toElement(\''. $form->getFieldvalue('gridid') .'\')');
		}
	}

	public function jumppage($parameters = array()){
		if(isset($parameters['gridid']) && isset($parameters['page'])){
			$grid = new mygrid($parameters['gridid']);

			if($parameters['page'] == 'prev'){
				$parameters['page'] = $grid->getPage() - 1;
			}
			elseif($parameters['page'] == 'next'){
				$parameters['page'] = $grid->getPage() + 1;
			}

			$grid->setPage($parameters['page']);

			$template = new ui($this);

			$template->assign('grid',$grid);

			if($parameters['history'] != 'history'){
				$this->response->addWaypoint( 'mygrid', 'jumppage', $grid->getId(), array('gridid' => $grid->getId(), 'page' => $parameters['page']));
			}

			$this->response->assign($parameters['gridid'] , 'innerHTML', $template->fetch($parameters['viewprefix'] . 'mygridstructure.tpl'));
			$this->response->script('var myFx = new Fx.Scroll(window).toElement(\''. $parameters['gridid'] .'\')');
		}
	}

	public function search($parameters = array()){
		$form = new form($parameters);

		$form->addField(new hiddenField('gridid' , $parameters['gridid'])); // We need to rely on the gridid being sent correctly

		$grid = new mygrid($parameters['gridid']);

		$form->addField(new inlinetextField('search' , 'Zoek ...', $parameters['search'] , array('required') ));
		$form->addField(new hiddenField('gridid' , $this->id));

		$form->setSubmittext('Zoek');

		if($form->validate() || $parameters['directsearch'] = 'true'){

			$model = $grid->getModel();
			$columns = $model->getColumns();


			$newcond = array();

			foreach($columns as $col){
				$tmp[] = array($col => array('mode' => '=', 'value' => '*' . $parameters['search'] . '*'));
			}

			$extra = $model->getExtrasearchconds($parameters['search'],$grid->getConditions());
			if(count($extra) > 0){
				foreach($extra as $extracond){
					$tmp[] = $extracond;
				}
			}

			$newcond = array('OR' => $tmp);
			if($grid->getConditions() != ''){
				$finalcond = array('AND' => array( $grid->getConditions() , $newcond ));
			}
			else {
				$finalcond = $newcond;
			}

			$grid->setConditions($finalcond);
			$grid->setCachedtotalpages('');

			$grid->setPage(1);
			$grid->setLastsearch($parameters['search']);

			$template = new ui($this);

			$template->assign('grid',$grid);

			if($parameters['history'] != 'history' && $parameters['directsearch'] != 'true'){
				$this->response->addWaypoint( 'mygrid', 'search', $grid->getId(), array('gridid' => $grid->getId(), 'search' => $parameters['search']));
			}

			$this->response->assign($parameters['gridid'] , 'innerHTML', $template->fetch($parameters['viewprefix'] . 'mygridstructure.tpl'));
		}
	}

	public function clearsearch($parameters = array()){
		if(isset($parameters['gridid'])){
			$grid = new mygrid($parameters['gridid']);

			$grid->setConditions('');
			$grid->setPage(1);
			$grid->setLastsearch('');
			$grid->setCachedtotalpages('');

			$template = new ui($this);

			$template->assign('grid',$grid);


			if($parameters['history'] != 'history' && $parameters['directsearch'] != 'true'){
				$this->response->addWaypoint( 'mygrid', 'clearsearch', $grid->getId(), array('gridid' => $grid->getId()));
			}

			$this->response->assign($parameters['gridid'] , 'innerHTML', $template->fetch($parameters['viewprefix'] . 'mygridstructure.tpl'));
		}
	}

	public function setObjectorder($parameters = array()){
		$id = $parameters['id'];
		$gridid = $parameters['gridid'];

		$popup = new popupController();

		$grid = new mygrid($gridid);
		$model = $grid->getModel();
		$linkmodel = $grid->getModel();

		$object = $model->getfromId($id);
		if(count($object) == 1){
			$object = $object[0];

			$view = new ui($this);

			$form = new form($parameters);
			$form->addField(new hiddenField('gridid',$gridid));
			$form->addField(new hiddenField('id',$id));

			$form->addField(new textField('order','Order',$object->_get($grid->getOrderfield()),array('required','numeric','range:1<->' . $model->getMax($grid->getOrderfield(),$grid->getConditions()))));

			if($form->validate()){

					$curlink = $object;

					if($object->_get($grid->getOrderfield()) > $form->getFieldvalue('order') ){
						$cond = array($grid->getOrderfield() => array('mode' => 'BETWEEN','value' => $form->getFieldvalue('order') , 'topvalue' => $object->_get($grid->getOrderfield())));

						$links = $linkmodel->get(array('AND' => array($cond,$grid->getConditions())));

						try {
							foreach($links as $link){
								$link->setOrder($link->getOrder() + 1 );
								$linkmodel->save($link);
							}

							$curlink->setOrder($form->getFieldvalue('order'));
							$linkmodel->save($curlink);
						}
						catch(Exception $e){
							$popup->createflash(array('name' => 'error', 'type' => 'error', 'content' => 'De wijziging is niet doorgevoerd! Raadpleeg de informaticadienst.'));
							return false;
						}
					}
					elseif($object->_get($grid->getOrderfield()) < $form->getFieldvalue('order')){
						$cond = array($grid->getOrderfield() => array('mode' => 'BETWEEN','value' =>  $object->_get($grid->getOrderfield()), 'topvalue' => $form->getFieldvalue('order')));

						$links = $linkmodel->get(array('AND' => array($cond,$grid->getConditions())));

						try {
							foreach($links as $link){
								$link->setOrder($link->getOrder() - 1 );
								$linkmodel->save($link);
							}

							$curlink->setOrder($form->getFieldvalue('order'));
							$linkmodel->save($curlink);
						}
						catch(Exception $e){
							$popup->createflash(array('name' => 'error', 'type' => 'error', 'content' => 'De wijziging is niet doorgevoerd! Raadpleeg de informaticadienst.'));
							return false;
						}
					}
					else {
						$popup->createflash(array('name' => 'warning', 'type' => 'warning', 'content' => 'De nieuwe plaats was dezelfde als de oude plaats. Er is dus niets gewijzigd.'));
						$this->response->assign('gridextra_' . $gridid , 'innerHTML', '');
						return false;
					}


				$popup->createflash(array('name' => 'success', 'type' => 'success', 'content' => 'De wijziging is goed doorgevoerd.'));

				$gridcontr = new mygridController();
				$gridcontr->reloadgrid($gridid);

				$this->response->assign('gridextra_' . $gridid , 'innerHTML', '');
			}
			elseif(!$form->isSent()){
				$view->assign('form',$form);
				$view->assign('closeextra',new ajaxrequest('mygrid','closeextra',array('id' => 'gridextra_' . $gridid)));
				$this->response->assign('gridextra_' . $gridid , 'innerHTML', $view->fetch($parameters['viewprefix'] . 'mygrid_setobjectorder.tpl'));
			}
	}
	}

	public function setOrder($parameters = array()){
		$col = $parameters['col'];

		if(isset($parameters['gridid'])){
			$grid = new mygrid($parameters['gridid']);

			$current = $grid->getOrder();

//			$model = $grid->getModel();
//			$assoc = $model->getAssoc();
//			if(isset($assoc[$col])){
//				if($assoc[$col]['type'] = 'map'){
//					$col = $assoc[$col]['relationkey'];
//				}
//			}

			if(is_array($current)){
				$field = $current['fields'][0];
				if($field == $col){
					if($current['type'] == 'ASC'){
						$type = 'DESC';
					}
					else {
						$type = 'ASC';
					}
				}
				else {
					$type = 'ASC';
				}
			}
			else {
				$type = 'ASC';
			}

			$grid->setOrder(array('fields' => array($col), 'type' => $type));

			$template = new ui($this);

			$template->assign('grid',$grid);

			$this->response->assign($parameters['gridid'] , 'innerHTML', $template->fetch($parameters['viewprefix'] . 'mygridstructure.tpl'));
		}
	}
}

?>