<?php
class myarticleModel extends mssqlmodel {
	protected $mapping = array('id' => 'id', 'alias' => 'alias', 'author' => 'author' , 'creationdate' => 'creationdate', 'authorname' => 'authorname');

	protected $assoc = array(
							'section' => array(
											'type' => 'habtm',
											'joinmodel' => 'myarticlesectionlinkModel',
											'class' => 'myarticlesectionlinkObject',
											'foreignkey' => 'articleid',
											'relationkey' => 'id',
											'assocforeignkey' => 'sectionid',
											'condition' => array(),
										),
							'version' => array(
											'type' => 'hasmany',
											'joinmodel' => 'myarticleversionModel',
											'class' => 'myarticleversionObject',
											'foreignkey' => 'articleid',
											'relationkey' => 'id',
											'assocforeignkey' => 'id',
											'condition' => array(),
										)
						);

	protected $datefields = array('creationdate');

	public function getExtrasearchconds($search,$cond){

		$baseset = $this->get($cond);
		$extracond = array();

		foreach($baseset as $row){

			$newcond = array();
			$tmp = array();
			$idcond = array('articleid' => array('mode' => '=','value' => $row->getId()));

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