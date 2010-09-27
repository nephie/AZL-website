<?php

class pageObject extends object {

	protected $id;
	protected $parentid;
	protected $redirectid;
	protected $order;
	protected $title;
	protected $template;

	protected $request = null;

	public function getRequest(){
		if( ! $this->request instanceof pagerequest ){
			$this->request = new pagerequest($this->id);
		}

		return $this->request;
	}

}

?>