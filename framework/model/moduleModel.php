<?php

class moduleModel extends mssqlmodel {

	protected $mapping = array('id'=>'id', 'name' => 'name', 'prefix' => 'prefix', 'title'=> 'title', 'action' => 'action' , 'arguments' => 'arguments');
	protected $arrayFields = array('arguments');
	protected $latesortfields  = array('arguments');

}
?>