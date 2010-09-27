<?php
//TODO: should the form take existing parameters along as hidden fields?
class form extends getandsetLib {
	protected $id;
	protected $request;

	protected $submittext = 'Doorsturen';
	protected $resettext = 'Herbeginnen';

	protected $sentdata = null;

	protected $validated = false;
	protected $sent = false;

	protected $field = array();

	protected $phased = false;
	protected $phasedrequest;
	protected $ready = false;

	protected $currentfields;
	protected $previousfields;

	protected $focusfield = '';
	protected $nofocus = false;

	public function form($data,$controller = '' , $action = ''){
		if(isset($data['hidden_form_id'])){
			$this->id = $data['hidden_form_id'];
			unset($this->sentdata['hidden_form_id']);

			$this->sentdata = $data;

			$this->sent = true;
		}
		else {
			$this->id = uniqid();
		}

		if(isset($_SESSION['form_' . $this->id])){
			$this->previousfields = unserialize(serialize(unserialize($_SESSION['form_' . $this->id])));
		}

		if($controller == '' || $action == '') {
			$trace = debug_backtrace();
		}

		if($action == '') {
			$action = $trace[1]['function'];
		}

		if($controller == ''){
			$controller = str_replace('Controller' , '' , $trace[1]['class']);
		}

		$this->request = new ajaxrequest($controller , $action);

	}

	public function setPhasedrequest($request){
		$this->phased = true;
		$this->phasedrequest = $request;
	}

	protected function confirm($controller, $title = ''){
		$view = new ui($controller);

		$view->assign('form',$this);
		$view->assign('title',$title);

		//$popup = new popupController();

		//$popup->create(array('name' => 'confirm','title' => $title, 'content' => $view->fetch('confirmform.tpl')));
		$response = responseLib::getInstance();
		$response->assign($controller->getSelf(),'innerHTML',$view->fetch('confirmform.tpl'));
	}

	public function confirmed($controller, $title = ''){

		$this->id .= '_confirm';
		$this->addField(new hiddenField('confirmed' , 'confirmed'));

		if($this->sentdata['confirmed'] == 'confirmed'){

			$popup = new popupController();
			$popup->destroy(array('name' => 'confirm'));

			foreach($this->sentdata as $key => $value){
				$this->sentdata[$key] = stripslashes($value);
			}

			return true;
		}
		else {
			$this->confirm($controller, $title);
		}

		return false;
	}

	public function validate(){

		if(count($this->sentdata) > 0){
			$this->validated = true;

			foreach ($this->field as $field){
				if(isset($this->sentdata[$field->getName()])){
					$value = $this->sentdata[$field->getName()];
				}
				else {
					$value = '';
				}
				if(!$field->validate($value , $this)){
					$this->validated = false;
				}
			}
		}

		return $this->validated;
	}

	public function clear(){
		unset($this->sentdata);

		$response = responseLib::getInstance();

		foreach ($this->field as $field) {
			$field->setValue($field->getDefaultvalue());
			$response->assign($field->getId() , 'value' , $field->getValue());
		}
	}

	public function addField($field){

		$this->currentfields[$field->getName()] = $field->getName();

		$_SESSION['form_' . $this->id] = serialize($this->currentfields);

		if(get_class($field) == 'checkboxField'){
			if(isset($this->sentdata[$field->getName()])){
				$field->setValue($this->sentdata[$field->getName()]);
				$field->setSelected(true);
			}
			elseif($this->isSent()){
				if($this->isPhased()){
					if(isset($this->previousfields[$field->getName()])){
						$field->setValue('');
						$field->setSelected(false);
					}
					else{
						if($field->isSelected()){
							$field->setValue($field->getDefaultvalue());
							$this->sentdata[$field->getName()] = $field->getDefaultvalue();
						}
					}
				}
				else {
					$field->setValue('');
					$field->setSelected(false);
				}
			}
		}
		elseif(get_class($field) == 'checkboxgroupField'){
			if(isset($this->sentdata[$field->getName()])){
				foreach($field->getBoxes() as $key => $box){
					if(in_array($key,$this->sentdata[$field->getName()])){
						$box['selected'] = true;
					} else {
						$box['selected'] = false;
					}

					$aboxes[$key] = $box;
				}

				$field->setBoxes($aboxes);
			}
			elseif($this->isSent()){
			if($this->isPhased()){
					if(isset($this->previousfields[$field->getName()])){
						foreach($field->getBoxes() as $key => $box){
							$box['selected'] = false;
							$aboxes[$key] = $box;
						}
						$field->setBoxes($aboxes);
					}
					else{
						foreach($field->getBoxes() as $key => $box){
							if($box['selected']){
								$this->sentdata[$field->getName()][] = $key;
								$field->setValue($this->sentdata[$field->getName()]);
							}
						}
					}
				}
				else {
					foreach($field->getBoxes() as $key => $box){
						$box['selected'] = false;
						$aboxes[$key] = $box;
					}
					$field->setBoxes($aboxes);
				}
			}
		}
		elseif(get_class($field) == 'selectField' || get_class($field) == 'suggestselectField'){
			if(isset($this->sentdata[$field->getName()])){
				if(is_array($this->sentdata[$field->getName()])){
					unset($this->sentdata[$field->getName()]['$family']);
					if(count($this->sentdata[$field->getName()]) > 0){
						$field->setValue($this->sentdata[$field->getName()]);
					}
				}
				else {
					if(count($this->sentdata[$field->getName()]) != ''){
						$field->setValue($this->sentdata[$field->getName()]);
					}
				}
			}
		}
		else
		{
			if(isset($this->sentdata[$field->getName()])){
				$field->setValue($this->sentdata[$field->getName()]);
			}
		}

		$field->setId($this->id . '_' . $field->getName());

        if($this->field == ''){
            $this->field = array();
        }
        else {
            if( ! is_array($this->field)){
                $tmp = $this->field;
                $this->field = array($tmp);
            }
        }
        $this->field[$field->getName()] = $field;
	}

	public function getFieldvalue($fieldName){
		if(isset($this->sentdata[$fieldName])){
			return $this->sentdata[$fieldName];
		}
		else {
			return '';
		}
	}

	public function setFieldvalue($fieldName,$value){
		$this->sentdata[$fieldName] = $value;
	}

	public function getFieldbyname($name){
		return $this->field[$name];
	}

	public function getFocus(){
		$fieldname = '';
		if($this->focusfield != ''){
			$fieldname = $this->focusfield;
		}
		else {
			foreach($this->field as $name => $field){
				if(get_class($field) != 'hiddenField'){
					$fieldname = $name;
					break;
				}
			}
		}

		if($fieldname != '' && !$this->nofocus){
			if(get_class($this->field[$fieldname]) == 'suggestselectField'){
				$fieldname .= '_text';
			}

			$response = responseLib::getInstance();
			$response->script('setTimeout("$(\'' . $this->id . '_' . $fieldname . '\').focus()",500)');
		}
	}
}

?>