<?php

class mymoduleadminController extends controller {

	public function managemodules($parameters = array()){
		$view = new ui($this);

		$grid = new mygrid('modules');
		$grid->setModel(new processedmoduleModel());
		$grid->setNosortfield(array('arguments'));

		$grid->registerAddrequest('mymoduleadmin','addmodule',array('title' => 'Module toevoegen'));
		$grid->registerEditrequest('mymoduleadmin','editmodule',array('title' => 'Module aanpassen','moduleid' => '{id}'));
		$grid->registerDeleterequest('mymoduleadmin','deletemodule',array('title' => 'Module verwijderen','moduleid' => '{id}'));

		$view->assign('grid',$grid);

		$this->response->assign($this->self,'innerHTML',$view->fetch('mymoduleadmin_managemodules.tpl'));
	}

	public function deletemodule($parameters = array()){
		$view = new ui($this);
		$flash = new popupController();

		$model = new processedmoduleModel();
		$modules = $model->getfromId($parameters['moduleid']);


		if(count($modules) == 1){
			$module = $modules[0];

			$linkmodel = new modulepageModel();

			$links = $linkmodel->getfromModuleid($parameters['moduleid']);

			$pages = array();
			if(count($links) > 0){
				$all = false;
				foreach($links as $link){
					$pageids[] = $link->getPageid();
					if($link->getPageid() == -1){
						$all = true;
					}
				}

				$pagemodel = new pageModel();
				$pages = $pagemodel->get(array('id' => array('mode' => 'IN', 'value' => $pageids)));

				if($all){
					$newpage = new pageObject();
					$newpage->setId(-1);
					$newpage->setTitle('Alle pagina\'s');

					array_unshift($pages,$newpage);
				}
			}

			if($parameters['sure'] == 'sure'){
				try {
					$linkmodel->deletebyModuleid($parameters['moduleid']);
					$model->delete($module);
				}
				catch(Exception $e){
					$flash->createflash(array('name' => 'error', 'type' => 'error', 'content' => 'De module werd niet goed verwijderd!'));
					return false;
				}

				$flash->createflash(array('name' => 'success', 'type' => 'success', 'content' => 'De module werd goed verwijderd.'));

				$this->response->assign('gridextra_' . $parameters['-gridid-'],'innerHTML','');

				return true;
			}
			else {
				$view->assign('module',$module);
				$view->assign('pages',$pages);

				return $view->fetch('mymoduleadmin_deletemodule.tpl');
			}
		}
		else {
			$flash->createflash(array('name' => 'error', 'type' => 'error', 'content' => 'De module werd niet gevonden!'));
			return false;
		}
	}

	public function editmodule($parameters = array()){
		$view = new ui($this);
		$flash = new popupController();

		$model = new moduleModel();

		$modules = $model->getfromId($parameters['moduleid']);

		if(count($modules) == 1){
			$module = $modules[0];

			$parameters['themodule'] = $module;
			$form = $this->buildeditform($parameters);

			if($form->validate()){

				$module->setTitle($form->getFieldvalue('name'));
				$module->setName($form->getFieldvalue('module'));
				$module->setAction($form->getFieldvalue('moduleaction'));

				foreach($form->getField() as $name => $field){
					if($name != 'moduleid' && $name != 'name' && $name != 'module' && $name != 'moduleaction' && $name != 'controller' && $name != 'action' && $name != '-gridid-'){
						$params[$name] = $form->getFieldvalue($name);
					}
				}
				$module->setArguments($params);

				try{
					$model->save($module);
				}
				catch(exception $e){
					$flash->createflash(array('name' => 'error', 'type' => 'error', 'content' => 'De module werd niet goed aangepast!'));
					return false;
				}

				$flash->createflash(array('name' => 'success', 'type' => 'success', 'content' => 'De module werd goed aangepast.'));

				$this->response->assign('gridextra_' . $parameters['-gridid-'],'innerHTML','');

				return true;
			}
			elseif(!$form->isSent()){
				$view->assign('form' , $form);
				return $view->fetch('mymoduleadmin_editmodule.tpl');
			}
			else {
				return false;
			}
		}
		else {
			$flash->createflash(array('name' => 'error', 'type' => 'error', 'content' => 'De module werd niet gevonden!'));
			return false;
		}
	}

	public function addmodule($parameters = array()){
		$form = $this->buildaddform($parameters);

		$view = new ui($this);

		if($form->validate()){

			$module = new moduleObject();
			$module->setTitle($form->getFieldvalue('name'));
			$module->setName($form->getFieldvalue('module'));
			$module->setAction($form->getFieldvalue('moduleaction'));

			foreach($form->getField() as $name => $field){
				if($name != 'name' && $name != 'module' && $name != 'moduleaction' && $name != 'controller' && $name != 'action' && $name != '-gridid-'){
					$params[$name] = $form->getFieldvalue($name);
				}
			}
			$module->setArguments($params);

			$module->setPrefix(uniqid(time()));

			$model = new moduleModel();

			$flash = new popupController();

			try{
				$model->save($module);
			}
			catch(exception $e){
				$flash->createflash(array('name' => 'error', 'type' => 'error', 'content' => 'De module werd niet goed toegevoegd!'));
				return false;
			}

			$flash->createflash(array('name' => 'success', 'type' => 'success', 'content' => 'De module werd goed toegevoegd.'));

			$this->response->assign('gridextra_' . $parameters['-gridid-'],'innerHTML','');

			return true;
		}
		elseif(!$form->isSent()){
			$view->assign('form' , $form);
			return $view->fetch('mymoduleadmin_addmodule.tpl');
		}
		else {
			return false;
		}
	}

	public function updateaddform($parameters = array()){
		$form = $this->buildaddform($parameters);

		$view = new ui($this);
		$view->assign('form' , $form);
		$this->response->assign('gridextra_' . $parameters['-gridid-'] . '_content','innerHTML', $view->fetch('mymoduleadmin_addmodule.tpl'));
	}

	public function buildaddform($parameters = array()){
		$form = new mygridform($parameters,$parameters['-gridid-'],'edit','mymoduleadmin','addmodule');
		$form->setPhasedrequest(new ajaxrequest('mymoduleadmin','updateaddform'));

		if(isset($parameters['__field__'])){
			$changedfield = $parameters['__field__'];
			$form->setFocusfield($changedfield);
		}

		$form->addField(new textField('name','Titel','',array('required')));

		$modules = $this->getModulelist();

		$moduleselect = new selectField('module','Module',array('required'));
		$moduleselect->addOption(new selectoptionField('','',true));

		foreach($modules as $module => $description){
			$moduleselect->addOption(new selectoptionField($module,$module,false,$description));
		}

		$form->addField($moduleselect);

		if($form->getFieldvalue('module') != ''){
			$actions = $this->getActions($form->getFieldvalue('module'));

			$actionselect = new selectField('moduleaction','Actie',array('required'));
			$actionselect->addOption(new selectoptionField('','',true));

			foreach($actions as $action => $actionconf){
				$actionselect->addOption(new selectoptionField($action,$action,false,$actionconf['description']));
			}

			$form->addField($actionselect);
		}

		if($form->getFieldvalue('moduleaction') != ''){
			$options = $this->getOptions($form->getFieldvalue('module'), $form->getFieldvalue('moduleaction'));

			foreach ($options as $option => $values){
				$tmp = new selectField($option,$option,array('required'));

				foreach($values as $id => $name){
					$tmp->addOption(new selectoptionField($name,$id));
				}

				$form->addField($tmp);
			}
		}

		return $form;
	}

	public function updateeditform($parameters = array()){

		$flash = new popupController();

		$model = new moduleModel();

		$modules = $model->getfromId($parameters['moduleid']);

		if(count($modules) == 1){
			$module = $modules[0];

			$parameters['themodule'] = $module;
			$form = $this->buildeditform($parameters);

			$view = new ui($this);
			$view->assign('form' , $form);
			$this->response->assign('gridextra_' . $parameters['-gridid-'] . '_content','innerHTML', $view->fetch('mymoduleadmin_addmodule.tpl'));
		}
		else {
			$flash->createflash(array('name' => 'error', 'type' => 'error', 'content' => 'De module werd niet gevonden!'));
			return false;
		}
	}

	public function buildeditform($parameters = array()){
		$form = new mygridform($parameters,$parameters['-gridid-'],'edit','mymoduleadmin','editmodule');
		$form->setPhasedrequest(new ajaxrequest('mymoduleadmin','updateeditform'));

		$themodule = $parameters['themodule'];

		if(isset($parameters['__field__'])){
			$changedfield = $parameters['__field__'];
			$form->setFocusfield($changedfield);
		}

		$form->addField(new hiddenField('moduleid',$parameters['moduleid']));

		$title = (isset($parameters['name']))? $parameters['name'] : $themodule->getTitle();
		$form->addField(new textField('name','Titel',$title,array('required')));


		$mod = (isset($parameters['module']))? $parameters['module'] : $themodule->getName();
		$modules = $this->getModulelist();
		$moduleselect = new selectField('module','Module',array('required'));

		foreach($modules as $module => $description){
			$selected = ($module == $mod) ? true : false;
			$moduleselect->addOption(new selectoptionField($module,$module,$selected,$description));
		}

		$form->addField($moduleselect);

		$act = (isset($parameters['moduleaction'])) ? $parameters['moduleaction'] : $themodule->getAction() ;
		$actions = $this->getActions($mod);
		$actionselect = new selectField('moduleaction','Actie',array('required'));

		if(!isset($actions[$act])){
			$act = $themodule->getAction();
			if(!isset($actions[$act])){
				$actionselect->addOption(new selectoptionField('','',true));
			}
		}

		foreach($actions as $action => $actionconf){
			$selected = ($action == $act) ? true : false;
			$actionselect->addOption(new selectoptionField($action,$action,$selected,$actionconf['description']));
		}

		$form->addField($actionselect);


		$options = $this->getOptions($mod, $act);
		$args = $themodule->getArguments();

		foreach ($options as $option => $values){
			$tmp = new selectField($option,$option,array('required'));
			$opt = (isset($parameters[$option])) ? $parameters[$option] : $args[$option];

			foreach($values as $id => $name){
				$selected = ($id == $opt) ? true : false;
				$tmp->addOption(new selectoptionField($name,$id,$selected));
			}

			$form->addField($tmp);
		}

		return $form;
	}


	public function getModulelist(){
		$modules = array();

		$dir = FRAMEWORK . DS . 'modules';

		$subs = scandir($dir);

		foreach($subs as $sub){
			if(is_dir($dir . DS . $sub)){
				if(file_exists($dir . DS . $sub . DS . 'module.php')){
					include($dir . DS . $sub . DS . 'module.php');
					$modules[$sub] = $description;
				}
			}
		}

		return $modules;
	}

	public function getActions($module){
		include(FRAMEWORK . DS . 'modules' . DS . $module . DS . 'module.php');

		return $actions;
	}

	public function getOptions($module,$action){
		include(FRAMEWORK . DS . 'modules' . DS . $module . DS . 'module.php');

		$result = array();

		if(count($actions[$action]['params']) > 0){

			foreach($actions[$action]['params'] as $option => $values){
				if(!is_array($values)){
					$confclass = $module . 'Config';
					$confobject = new $confclass();
					$values = $confobject->$values();
				}

				$result[$option] = $values;
			}
		}

		return $result;
	}

}
?>