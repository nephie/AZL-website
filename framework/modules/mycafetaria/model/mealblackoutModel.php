<?php

class mealblackoutModel extends mssqlmodel {

	protected $mapping = array('id' => 'id', 'blackoutperiodstart' => 'blackoutperiodstart' , 'blackoutperiodend' => 'blackoutperiodend', 'triggertime' => 'triggertime' , 'days' => 'days');

	protected $assoc = array(
							'mealid' => array(
											'type' => 'habtm',
											'joinmodel' => 'mealblackoutmealModel',
											'class' => 'mealblackoutmealObject',
											'foreignkey' => 'blackoutid',
											'relationkey' => 'id',
											'assocforeignkey' => 'mealid',
											'condition' => array(),
										)
						);
}

?>