<?php

class mygrid extends viewstategetandsetLib
{
	protected $id;

	protected $model;
	protected $conditions;
	protected $column;
	protected $request;

	protected $defaultorder;
	protected $defaultpagesize;
	protected $defaultconditions;

	protected $order;
	protected $pagesize;
	protected $orderfield;
	protected $nosortfield;

	protected $modelname;

	protected $page;

	protected $lastsearch;

	protected $cachedtotalpages;


	public function __construct($id){
		$this->id = $id;
		$this->namespace = $this->id;
		$this->conditions = '';
		$this->defaultpagesize = 10;

		$this->page = 1;


		$old = myviewstate::rebuild($this,$this->id);

		if($this->modelname != ''){
			$this->model = new $this->modelname();
		}

		$response = responseLib::getInstance();
	}

	public function setModel($model){
		$this->model = $model;
		$this->setModelname(get_class($model));
	}

	public function setPage($page){
		$this->page = $page;
	}

	public function setNosortfield($fields){
		$this->nosortfield = $fields;
		myviewstate::set($this->namespace,'nosortfield',$this->nosortfield);
	}

	public function setCachedtotalpages($cachedtotalpages){
		$this->cachedtotalpages = $cachedtotalpages;
		myviewstate::set($this->namespace,'cachedtotalpages',$this->cachedtotalpages);
	}

	public function setLastsearch($search){
		$this->lastsearch = $search;
		myviewstate::set($this->namespace,'lastsearch',$this->lastsearch);
	}

	public function registerRequest($column,$controller,$action,$parameters){
		$this->request[$column] = array('controller' => $controller, 'action' => $action, 'parameters' => $parameters);
		myviewstate::set($this->namespace,'request',$this->request);
	}

	public function registerEditrequest($controller,$action,$parameters){
		$this->request['-edit-'] = array('controller' => 'mygrid', 'action' => 'editrequest', 'parameters' => array_merge(array('controller' => $controller, 'action' => $action),$parameters));
		myviewstate::set($this->namespace,'request',$this->request);
	}

	public function registerAddrequest($controller,$action,$parameters){
		$this->request['-add-'] = array('controller' => 'mygrid', 'action' => 'editrequest', 'parameters' => array_merge(array('controller' => $controller, 'action' => $action),$parameters));
		myviewstate::set($this->namespace,'request',$this->request);
	}

	public function registerDeleterequest($controller,$action,$parameters){
		$this->request['-delete-'] = array('controller' => 'mygrid', 'action' => 'deleterequest', 'parameters' => array_merge(array('controller' => $controller, 'action' => $action),$parameters));
		myviewstate::set($this->namespace,'request',$this->request);
	}

	public function unregisterRequest($col){
		unset($this->request[$col]);
		myviewstate::set($this->namespace,'request',$this->request);
	}

	public function getRequest($column, $object = NULL){
		if(isset($this->request[$column])){

				foreach($this->request[$column]['parameters'] as $key => $param) {
					$matches = array();
					if(preg_match("/(\{)(.*)(\})/", $param, $matches)){
						if(is_object($object) && $matches[2] != 'this'){
							$param = $object->_get($matches[2]);
						}
						else{
							return false;
						}
					}
					$params[$key] = $param;
				}

			$params['-gridid-'] = $this->getId();

			if(isset($this->request[$column]['parameters']['myacl'])){
				$acl = $this->request[$column]['parameters']['myacl'];
				if($acl['target'] == '{this}' && is_object($object)){
					$acl['target'] = $object;
				}
				if(myacl::isAllowed(myauth::getCurrentuser(),$acl['target'],$acl['right'],$acl['default'])){
					return new ajaxrequest($this->request[$column]['controller'], $this->request[$column]['action'], $params);
				}
				else {
					return false;
				}
			}
			else {
				return new ajaxrequest($this->request[$column]['controller'], $this->request[$column]['action'], $params);
			}

		}
		else {
			return false;
		}
	}

	public function getOrder(){
		if(!is_array($this->order)){
			return $this->defaultorder;
		}
		else {
			return $this->order;
		}
	}

	public function getPagesize(){
		if($this->pagesize == ''){
			return $this->defaultpagesize;
		}
		else {
			return $this->pagesize;
		}
	}

	public function getConditions(){
		if($this->conditions == ''){
			return $this->defaultconditions;
		}
		else {
			return $this->conditions;
		}
	}

	public function getColumn(){
		if(count($this->column) == 0){
			return $this->model->getColumns();
		}
		else {
			return $this->column;
		}
	}

	public function getSearchcolumn(){
		if(count($this->searchcolumn) == 0){
			return $this->model->getColumns();
		}
		else {
			return $this->searchcolumn;
		}
	}

	public function getTotalpages() {
		$size = $this->getPagesize();
		if($size == ''){
			return 1;
		}
		else {
			if($this->cachedtotalpages != ''){
					return $this->cachedtotalpages;
			}
			else {
				$count = $this->model->getcount($this->getConditions());
				$this->setCachedtotalpages(ceil($count/$size));
				return $this->cachedtotalpages;
			}
		}
	}

	public function getPage(){
		return ($this->page > $this->getTotalpages())? $this->getTotalpages() : $this->page;
	}

	public function getRow() {
		if($this->getPagesize() == '') {
			$offset = 0;
		}
		else {
			$offset = ($this->getPage() - 1 ) * $this->getPagesize();
		}

		if($this->getPagesize() == '') {
			$size = $this->model->getcount($this->getConditions());
		}
		//	MS sql is a bit stupid when it comes to limits so we need to calculate the pagesize for the last page ourself
		elseif(($this->getPagesize() + $offset) > $this->model->getcount($this->getConditions())){
			$size = $this->model->getcount($this->getConditions()) - $offset;
		}
		else {
			$size = $this->getPagesize();
		}

		$order = $this->getOrder();
		$col = $order['fields'][0];
		$cols = $this->model->getColumns();

		if(isset($cols[$col])){
			$result = $this->model->get($this->getConditions() , $this->getOrder(), $size , $offset);
		}
		elseif(is_array($order)){
			$result = $this->model->get($this->getConditions() , '', $size , $offset);

			if($order['type'] == 'ASC'){
				usort($result,create_function('$a,$b','return ($a->_get("' . $col . '") == $b->_get("' . $col . '"))?0:(($a->_get("' . $col . '") > $b->_get("' . $col . '"))?1:-1);'));
			}
			else {
				usort($result,create_function('$a,$b','return ($a->_get("' . $col . '") == $b->_get("' . $col . '"))?0:(($a->_get("' . $col . '") > $b->_get("' . $col . '"))?-1:1);'));
			}
		}
		else {
			$result = $this->model->get($this->getConditions() , '', $size , $offset);
		}

		return $result;
	}

	public function getGotopageform(){
		$form = new form(array(),'mygrid','gotopage');

		$form->addField(new inlinetextField('page' , 'Spring naar ...', ''  , array('required', 'numeric' , 'range:1<->' . $this->getTotalpages())));
		$form->addField(new hiddenField('gridid' , $this->id));

		$form->setSubmittext('Spring');

		return $form;
	}

	public function getSearchform(){
		$form = new form(array(),'mygrid','search');

		if($this->getLastsearch() != ''){
			$def = $this->getLastsearch();
		}
		else {
			$def = 'Zoek ...';
		}
		$form->addField(new inlinetextField('search' , $def, '' , array('required')));
		$form->addField(new hiddenField('gridid' , $this->id));

		$form->setSubmittext('Zoek');

		return $form;
	}

	public function getClearsearchrequest(){
		return new ajaxrequest('mygrid','clearsearch',array('gridid' => $this->id));
	}

	public function getGotofirstpagerequest(){
		return new ajaxrequest('mygrid','jumppage',array('gridid' => $this->id,'page' => 1));
	}

	public function getGotopreviouspagerequest(){
		return new ajaxrequest('mygrid','jumppage',array('gridid' => $this->id,'page' => $this->getPage() - 1));
	}

	public function getGotonextpagerequest(){
		return new ajaxrequest('mygrid','jumppage',array('gridid' => $this->id,'page' => $this->getPage() + 1));
	}

	public function getGotolastpagerequest(){
		return new ajaxrequest('mygrid','jumppage',array('gridid' => $this->id,'page' => $this->getTotalpages()));
	}

	public function getSetorderrequest($col){
		return new ajaxrequest('mygrid','setorder',array('gridid' => $this->id,'col' => $col));
	}

	public function getSetobjectorderrequest($id){
		return new ajaxrequest('mygrid','setobjectorder',array('gridid' => $this->id,'id' => $id));
	}

	public function isEditAllowed(){
		return (isset($this->request['-edit-']))? true : false;
	}

	public function isAddAllowed(){
		return (isset($this->request['-add-']))? true : false;
	}

	public function isDeleteAllowed(){
		return (isset($this->request['-delete-']))? true : false;
	}
}

?>