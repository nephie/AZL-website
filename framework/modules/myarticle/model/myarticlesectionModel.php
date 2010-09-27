<?php
class myarticlesectionModel extends mssqlmodel {
	protected $mapping = array('id' => 'id','name' => 'name');

	protected $assoc = array(
							'articleid' => array(
											'type' => 'habtm',
											'joinmodel' => 'myarticlesectionlinkModel',
											'class' => 'myarticlesectionlinkObject',
											'foreignkey' => 'sectionid',
											'relationkey' => 'id',
											'assocforeignkey' => 'articleid',
											'condition' => array(),
											'extrafields' => array('order' => 'order')
											)
	);
}
?>