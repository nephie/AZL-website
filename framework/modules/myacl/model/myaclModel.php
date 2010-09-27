<?php

class myaclModel extends mssqlmodel {

	protected $mapping = array('id' => 'id' , 'requester_type' => 'requestertype', 'requester_id' => 'requesterid' , 'object_type' => 'objecttype', 'object_id' => 'objectid' , 'right' => 'right' , 'allow' => 'allow');

}

?>