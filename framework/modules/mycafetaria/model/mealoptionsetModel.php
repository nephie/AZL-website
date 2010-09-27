<?php

class mealoptionsetModel extends mssqlmodel {

	protected $mapping = array('id' => 'id', 'name' => 'name', 'optionsettypeid' => 'optionsettypeid' , 'place' => 'place' , 'conditionaldefault' => 'conditionaldefault');

	protected $assoc = array(
							'optionid' => array(
											'type' => 'habtm',
											'joinmodel' => 'mealoptionsetoptionModel',
											'class' => 'mealoptionsetoptionObject',
											'foreignkey' => 'optionsetid',
											'relationkey' => 'id',
											'assocforeignkey' => 'optionid',
											'condition' => array(),
											'extrafields' => array('enabled' => 'enabled')
										),
							'mealid' => array(
											'type' => 'habtm',
											'joinmodel' => 'mealoptionsetlinkModel',
											'class' => 'mealoptionsetlinkObject',
											'foreignkey' => 'optionsetid',
											'relationkey' => 'id',
											'assocforeignkey' => 'mealid',
											'condition' => array(),
										)
						);

}

?>