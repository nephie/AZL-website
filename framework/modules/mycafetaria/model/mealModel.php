<?php

class mealModel extends mssqlmodel {

	protected $mapping = array('id' => 'id', 'name' => 'name' , 'mealtypeid' => 'mealtypeid', 'price' => 'price', 'price2' => 'price2');

	protected $assoc = array(
							'optionsetid' => array(
											'type' => 'habtm',
											'joinmodel' => 'mealoptionsetlinkModel',
											'class' => 'mealoptionsetlinkObject',
											'foreignkey' => 'mealid',
											'relationkey' => 'id',
											'assocforeignkey' => 'optionsetid',
											'condition' => array(),
										),
							'blackoutid' => array(
											'type' => 'habtm',
											'joinmodel' => 'mealblackoutmealModel',
											'class' => 'mealblackoutmealObject',
											'foreignkey' => 'mealid',
											'relationkey' => 'id',
											'assocforeignkey' => 'blackoutid',
											'condition' => array(),
										)
						);

}

?>