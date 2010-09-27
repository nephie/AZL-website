<?php

class myacl {

	public static function isAllowed($requester , $object , $right , $default = false){

		if($requester instanceof userObject){
			require(FRAMEWORK . DS . 'conf' . DS . 'myacl.php');
			$groups = $requester->getGroupid();
			if(isset($groups[$admingroup])){
				return true;
			}
		}
		elseif($requester instanceof groupObject){
			require(FRAMEWORK . DS . 'conf' . DS . 'myacl.php');
			$groups = $requester->getGroupid();
			if(isset($groups[$admingroup]) || $requester->getId() == $admingroup){
				return true;
			}
		}

		$requesterRelated = array();
		if($requester instanceof myaclInterface ){
			$requesterRelated = $requester->getMyaclrelated();
		}

		$objectRelated = array();
		if($object instanceof myaclInterface){
			$objectRelated = $object->getMyaclrelated();
		}

		$requestertype = get_class($requester);
		$objecttype = get_class($object);

		$requesterid = $requester->getId();
		$objectid = $object->getId();

		$model = new myaclModel();

		//	Get all related rights
		//	Group them by type
		$reqRelated = array();
		foreach ($requesterRelated as $rRelated){
			$reqRelated[$rRelated['type']][] = $rRelated['id'];
		}
		$reqRelated[$requestertype][] = $requesterid;

		$objRelated = array();
		foreach ($objectRelated as $oRelated){
			$objRelated[$oRelated['type']][] = $oRelated['id'];
		}
		$objRelated[$objecttype][] = $objectid;


		foreach ($reqRelated as $type => $ids) {
			$tcond = array( 'requestertype' => array(
											'mode' => '=',
											'value' => $type
										));
			$vcond = array('requesterid' => array(
								'mode' => 'IN',
								'value' => $ids
							));
			$reqCond['OR'][] = array('AND' => array($tcond , $vcond));
		}

		foreach ($objRelated as $type => $ids) {
			$otcond = array( 'objecttype' => array(
											'mode' => '=',
											'value' => $type
										));
			array_push($ids,'_ALL_');
			$ovcond = array('objectid' => array(
								'mode' => 'IN',
								'value' => $ids
							));
			$objCond['OR'][] = array('AND' => array($otcond , $ovcond));
		}

		$rightCond = array('right' => array(
										'mode' => 'IN',
										'value' => array($right , '_ALL_')
						));

		$cond['AND'] = array($reqCond , $objCond , $rightCond);


		$relatedRights = $model->get($cond);

		if(count($relatedRights) > 0){
			foreach ($relatedRights as $right) {
				if($right->getAllow() == 0){
					//	Denied
					return false;
				}
			}

			// Rights were specified, and none was a "denied" ... so they all were "allows" ... access given
			return true;
		}
		else {
			//	Nothing specified , return default
			return $default;
		}
	}

	public static function setAcl($requester,$object,$right,$allow){
		$model = new myaclModel();

		$requestertype = get_class($requester);
		$objecttype = get_class($object);

		$requesterid = $requester->getId();
		$objectid = $object->getId();

		$cond = array('AND' => array(
										array('requestertype' => array('mode' => '=' , 'value' => $requestertype)),
										array('requesterid' => array('mode' => '=' , 'value' => $requesterid)),
										array('objecttype' => array('mode' => '=' , 'value' => $objecttype)),
										array('objectid' => array('mode' => '=' , 'value' => $objectid)),
										array('right' => array('mode' => '=','value' => $right))
									)
						);

		$current = $model->get($cond);

		if(count($current) == 1){
			$newacl = $current[0];
			$newacl->setAllow($allow);
		}
		else {
			$newacl = new myaclObject();

			$newacl->setRequestertype($requestertype);
			$newacl->setRequesterid($requesterid);
			$newacl->setObjecttype($objecttype);
			$newacl->setObjectid($objectid);
			$newacl->setRight($right);
			$newacl->setAllow($allow);
		}

		try {
			$model->save($newacl);
		}
		catch (Exception $e){
			throw new Exception('Could not set ACL: ' . $e->getMessage());
		}
	}

	public static function delAcl($requester,$object,$right){
		$model = new myaclModel();

		$requestertype = get_class($requester);
		$objecttype = get_class($object);

		$requesterid = $requester->getId();
		$objectid = $object->getId();

		$cond = array('AND' => array(
										array('requestertype' => array('mode' => '=' , 'value' => $requestertype)),
										array('requesterid' => array('mode' => '=' , 'value' => $requesterid)),
										array('objecttype' => array('mode' => '=' , 'value' => $objecttype)),
										array('objectid' => array('mode' => '=' , 'value' => $objectid)),
										array('right' => array('mode' => '=','value' => $right))
									)
						);

		try {
			$model->delete($cond);
		}
		catch(Exception $e){
			throw new Exception('Could not delete ACL: ' . $e->getMessage());
		}
	}
}

?>