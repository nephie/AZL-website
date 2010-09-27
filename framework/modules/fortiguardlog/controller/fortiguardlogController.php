<?php
class fortiguardlogController extends controller {
	public function showblocked($parameter = array()){
		$view = new ui($this);

		$grid = new mygrid('blocked');
		$grid->setModel(new ftgdblockedModel());
		$grid->setDefaultorder(array('fields' => array('time'), 'type' => 'DESC'));
		$grid->setPagesize(15);

		$grid->registerRequest('user','mygrid','search',array('directsearch' => 'true','gridid' => $grid->getId(), 'search' => '{user}'));
		$grid->registerRequest('group','mygrid','search',array('directsearch' => 'true','gridid' => $grid->getId(), 'search' => '{group}'));
		$grid->registerRequest('sourceip','mygrid','search',array('directsearch' => 'true','gridid' => $grid->getId(), 'search' => '{sourceip}'));
		$grid->registerRequest('host','mygrid','search',array('directsearch' => 'true','gridid' => $grid->getId(), 'search' => '{host}'));
		$grid->registerRequest('cat','mygrid','search',array('directsearch' => 'true','gridid' => $grid->getId(), 'search' => '{cat}'));
		$grid->registerRequest('destip','mygrid','search',array('directsearch' => 'true','gridid' => $grid->getId(), 'search' => '{destip}'));

		$view->assign('blocked',$grid);
		$this->response->assign($this->self,'innerHTML',$view->fetch('ftgd_blocked.tpl'));
	}

	public function showallowed($parameter = array()){
		$view = new ui($this);

		$grid = new mygrid('allowed');
		$grid->setModel(new ftgdallowedModel());
		$grid->setDefaultorder(array('fields' => array('time'), 'type' => 'DESC'));
		$grid->setPagesize(15);

		$grid->registerRequest('user','mygrid','search',array('directsearch' => 'true','gridid' => $grid->getId(), 'search' => '{user}'));
		$grid->registerRequest('group','mygrid','search',array('directsearch' => 'true','gridid' => $grid->getId(), 'search' => '{group}'));
		$grid->registerRequest('sourceip','mygrid','search',array('directsearch' => 'true','gridid' => $grid->getId(), 'search' => '{sourceip}'));
		$grid->registerRequest('host','mygrid','search',array('directsearch' => 'true','gridid' => $grid->getId(), 'search' => '{host}'));
		$grid->registerRequest('cat','mygrid','search',array('directsearch' => 'true','gridid' => $grid->getId(), 'search' => '{cat}'));
		$grid->registerRequest('destip','mygrid','search',array('directsearch' => 'true','gridid' => $grid->getId(), 'search' => '{destip}'));

		$view->assign('blocked',$grid);
		$this->response->assign($this->self,'innerHTML',$view->fetch('ftgd_allowed.tpl'));
	}
}
?>