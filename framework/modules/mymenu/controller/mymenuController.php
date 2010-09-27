<?php

class mymenuController extends controller {

	private $tree;
	private $tree_id;
	private $depth;

	public function index($parameters){

		$pageModel = new pageModel();

		$view = new ui($this);

		$view->assign('pages' , $allowedPages);

		$startlevel = (isset($parameters['startlevel']))? $parameters['startlevel'] : 1;

		$id = myauth::getCurrentpageid();
		$page = $pageModel->getfromId($id);
		$page = $page[0];
		$tree[] = $page;

		while($page->getParentid() != 0){
			$page = $pageModel->getfromId($page->getParentid());
			$page = $page[0];
			$tree[] = $page;
			$this->tree_id[$page->getId()] = $page->getId();
		}
		$this->tree = array_reverse($tree);

		if($startlevel != 1){
			$startbelow = $this->tree[$startlevel-2]->getId();
		}
		else {
			$startbelow = 0;
		}

		$this->maxdepth = (isset($parameters['maxdepth']))? $parameters['maxdepth'] : -1;
		$depth = 1;
		$pages = $this->recursivegetpages($startbelow , $depth);

		$view->assign('menu' , $pages);

		$this->response->assign($this->self , 'innerHTML' , $view->fetch($parameters['viewprefix'] . 'menu_index.tpl'));
	}

	private function recursivegetpages($parent, $depth){
		$output = array();

		$pageModel = new pageModel();

		$target = new securitytarget();
		$target->setId('menu');

		$pages = $pageModel->get(array('parentid' => array('mode' => '=', 'value' => $parent)) , array('fields' => array('order') , 'type' => 'ASC'));

		foreach($pages as $page){
			$tmp = array();
			if(myacl::isAllowed(myauth::getCurrentuser() , $page , 'view') && myacl::isAllowed($page,$target,'show',true)){
				$tmp['page'] = $page;
				if($depth < $this->maxdepth || $this->maxdepth == -1){
					$tmp['subpages'] = $this->recursivegetpages($page->getId(),$depth + 1);
				}

				if(count($tmp['subpages']) == 0){
					unset($tmp['subpages']);
					$tmp['status_subpages'] = 'nosubpages';
				}
				else {
					$tmp['status_subpages'] = "subpages";
				}

				if($page->getId() == myauth::getCurrentpageid()){
					$tmp['status'] = 'active';
				}
				elseif (isset($this->tree_id[$page->getId()])) {
					$tmp['status'] = 'subpage_active';
				}
				else {
					$tmp['status'] = 'inactive';
				}

				$output[] = $tmp;
			}
		}


		return $output;
	}
}

?>