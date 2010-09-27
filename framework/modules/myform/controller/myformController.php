<?php

class myformController extends controller {

	public function updatesuggestfield($parameters = array()){
		$result = array();

		if($parameters['value'] != ''){

			$controller = $parameters['callbackcontroller'] . 'Controller';
			$function = $parameters['callbackfunction'];

			$theController = new $controller();

			$result = $theController->$function(rawurldecode($parameters['value']));

			$view = new ui($this);

			$view->assign('result', $result);
			$view->assign('id',$parameters['id']);

			$this->response->assign($parameters['id'] . '_result', 'innerHTML' , $view->fetch('myform_suggestresult.tpl'));
			$this->response->assign($parameters['id'] . '_result', 'style.display', 'block');

			$id = $parameters['id'];
			$script .= "
				currentsuggestresult['$id'] = -1;
				maxsuggestresult['$id'] = " . (count($result) - 1) . ";
			";

			$this->response->script($script);

			$script2 = "suggest_fixie('$id');";
			$this->response->script($script2);
		}
		else {
			$this->response->assign($parameters['id'] . '_result', 'innerHTML' , '');
			$this->response->assign($parameters['id'] . '_result', 'style.display', 'none');
		}
	}

	public function updatesuggestselectfield($parameters = array()){
		$result = array();
		$view = new ui($this);

		if($parameters['value'] != ''){

			$controller = $parameters['callbackcontroller'] . 'Controller';
			$function = $parameters['callbackfunction'];

			$extra = $parameters['extraparams'];

			$extralist = explode(',',$extra);
			$extraparams = array();

			foreach($extralist as $extraparam){
				list($key,$value) = explode('|',$extraparam);
				$extraparams[$key] = $value;
			}

			$theController = new $controller();

			$result = $theController->$function(rawurldecode($parameters['value']),$extraparams);

			$form = new form($parameters);

			list($id,$name) = explode('_',$parameters['id'],2);

			$form->setId($id);
			$select = new selectField(str_replace('_text','', $name),'','',true);
			$form->addField($select);

			foreach($result as $row){
				$select->addOption(new selectoptionField($row,$row,false));
			}


			$view->assign('form',$form);
			$view->assign('field', $select);


			$this->response->assign(str_replace('_text','', $parameters['id']) . '_container', 'innerHTML' , $view->fetch('selectField.tpl'));
		}
		else {
			$form = new form($parameters);
			$select = new selectField($parameters['id'],'','',true);

			$view->assign('form',$form);
			$view->assign('field', $select);

			$this->response->assign(str_replace('_text','', $parameters['id']) . '_container', 'innerHTML' , $view->fetch('selectField.tpl'));
		}
	}


}

?>