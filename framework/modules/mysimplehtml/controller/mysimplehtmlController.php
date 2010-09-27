<?php

class mysimplehtmlController extends controller {

	public function showhtml($parameters = array()){
		if(isset($parameters['template'])){
			$template = new ui($this);

			$this->response->assign($this->self,'innerHTML',$template->fetch($parameters['template'] . '.tpl'));
		}
	}
}
?>