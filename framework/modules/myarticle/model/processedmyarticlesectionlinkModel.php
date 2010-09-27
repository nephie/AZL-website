<?php
class processedmyarticlesectionlinkModel extends myarticlesectionlinkModel {
	protected $table = 'app_myarticlesectionlink';

	protected function fillObject($data){
		$object = parent::fillObject($data);

		$sectionmodel = new myarticlesectionModel();
		$articlemodel = new myarticleModel();

		$section = $sectionmodel->getfromId($object->getSectionid());
		if(count($section) == 1){
			$object->setSectionname($section[0]->getName());
		}

		$article = $articlemodel->getfromId($object->getArticleid());
		if(count($article) == 1){
			$object->setAlias($article[0]->getAlias());
			$object->setArticleauthorname($article[0]->getAuthorname());
			$object->setArticlecreationdate($article[0]->getCreationdate());
		}

		return $object;
	}

	public function getExtrasearchconds($search,$cond){

		$baseset = $this->get($cond);
		$extracond = array();

		foreach($baseset as $row){

			$newcond = array();
			$tmp = array();
			$idcond = array('articleid' => array('mode' => '=','value' => $row->getArticleid()));

			$model = new myarticleversionModel();
			$columns = $model->getColumns();

			foreach($columns as $col){
				$tmp[] = array($col => array('mode' => '=', 'value' => '*' . $search . '*'));
			}
			$newcond = array('AND'=> array($idcond,array('OR' => $tmp)));

			$res = $model->get($newcond);
			if(count($res) > 0){
				$extracond[] = array('id' => array('mode' => '=','value' => $row->getId()));
			}
		}

		return $extracond;
	}
}
?>