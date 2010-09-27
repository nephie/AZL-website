<?php

class mealoptionModel extends mssqlmodel {

	protected $mapping = array('id' => 'id', 'name' => 'name', 'price' => 'price', 'price2' => 'price2');

	protected $assoc = array(

							'optionsetid' => array(
											'type' => 'habtm',
											'joinmodel' => 'mealoptionoptionsetModel',
											'class' => 'mealoptionoptionsetObject',
											'foreignkey' => 'optionid',
											'relationkey' => 'id',
											'assocforeignkey' => 'optionsetid',
											'condition' => array(),
										),
							'optionsetid2' => array(
											'type' => 'habtm',
											'joinmodel' => 'mealoptionsetoptionModel',
											'class' => 'mealoptionsetoptionObject',
											'foreignkey' => 'optionid',
											'relationkey' => 'id',
											'assocforeignkey' => 'optionsetid',
											'condition' => array(),
										)
						);
}

?>