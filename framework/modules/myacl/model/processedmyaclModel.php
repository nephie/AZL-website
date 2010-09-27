<?php

class processedmyaclModel extends myaclModel {

	protected $table = 'app_myacl';
	protected $extracols = array('requester','object','rightdesc');


	protected function fillObject($data){
		$acl = parent::fillObject($data);

		$requestertype = $acl->getRequestertype();
		$rc = new ReflectionClass($requestertype);
		if($rc->hasMethod('getMyacldisplayfield')){
			$requestermodel = str_ireplace('Object','Model',$requestertype);

			$thereqModel = new $requestermodel();
			$requester = $thereqModel->getfromId($acl->getRequesterid());

			if(count($requester) == 1){
				$acl->setRequester($requester[0]->getMyacldisplayfield());
			}
			else {
				$acl->setRequester($acl->getRequesterid());
			}
		}
		else {
			$acl->setRequester($acl->getRequesterid());
		}

		if($acl->getObjecttype() == 'securitytarget'){
			$acl->setObject($acl->getObjectid());
		}
		else {
			$objecttype = $acl->getObjecttype();
			$rco = new ReflectionClass($objecttype);
			if($rco->hasMethod('getMyacldisplayfield')){
				$objectmodel = str_ireplace('Object','Model',$objecttype);

				$theobjectModel = new $objectmodel();
				$object = $theobjectModel->getfromId($acl->getObjectid());

				if(count($object) == 1){
					$acl->setObject($object[0]->getMyacldisplayfield());
				}
				else {
					$acl->setObject($acl->getObjectid());
				}
			}
			else {
				$acl->setObject($acl->getObjectid());
			}
		}

		include(FRAMEWORK . DS . 'conf' . DS . 'myacl.php');
		if($acl->getRight() == '_ALL_'){
			$acl->setRightdesc('Full control');
		}
		elseif ($acl->getRight() == 'managerights'){
			$acl->setRightdesc('Rechten beheren');
		}
		else{

			if($acl->getObjecttype() == 'securitytarget'){
				$type = $acl->getObjectid();
			}
			else {
				$type = $acl->getObjecttype();
			}

			$right = $acl->getRight();

			if(isset($myacl[$type]['rights'][$right]['description'])){
				$acl->setRightdesc($myacl[$type]['rights'][$right]['description']);
			}
			else {
				$acl->setRightdesc($acl->getRight());
			}
		}

		return $acl;
	}

	public function getExtrasearchconds($search,$cond){

			$baseset = $this->get($cond);

			$extracond = array();
			foreach($baseset as $row){
				if(stripos($row->getRequester(),$search) !== false){
					$extracond[] = array( 'AND' => array(
														array('requesterid' => array('mode' => '=','value'=> $row->getRequesterid())),
														array('requestertype' => array('mode' => '=','value'=> $row->getRequestertype()))
													)
									);
				}
				elseif(stripos($row->getObject(),$search) !== false){
					$extracond[] = array( 'AND' => array(
														array('objectid' => array('mode' => '=','value'=> $row->getObjectid())),
														array('objecttype' => array('mode' => '=','value'=> $row->getObjecttype()))
													)
									);
				}
			}

		$parent = parent::getExtrasearchconds($search,$cond);

		if(count($parent) > 0){
			$extracond = array_merge($parent,$extracond);
		}

		return $extracond;
	}

	public function getmyclause($cond){
		return $this->getClause($cond);
	}
}

?>