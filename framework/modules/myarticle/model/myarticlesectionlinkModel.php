<?php
class myarticlesectionlinkModel extends mssqlmodel {
	protected $mapping = array('id' => 'id','articleid' => 'articleid','sectionid' => 'sectionid', 'order' => 'order');

	public function delete($conditions = array()){

		$cur = $this->get($conditions);

		foreach($cur as $curlink){
				$cond = array('order' => array('mode' => '>','value' => $curlink->getOrder()));
				$idcond = array('sectionid' => array('mode' => '=','value' => $curlink->getSectionid()));
				$links = $this->get(array('AND' => array($cond,$idcond)));

				try {
					foreach($links as $link){
						$link->setOrder($link->getOrder() - 1);
						$this->save($link);
					}
				}
				catch(Exception $e){
					throw new deleteException($e->getMessage());
				}
		}

		parent::delete($conditions);
	}
}
?>