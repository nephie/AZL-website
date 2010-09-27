<?php

class processedmealoptionsetModel extends mealoptionsetModel {

	protected $table = 'app_mealoptionset';


	protected function fillObject($data){
		$object = parent::fillObject($data);

		$model = new mealoptionsettypeModel();

		$type = $model->getfromId($object->getOptionsettypeid());

		if(count($type) == 1){
			$type = $type[0];

			$object->setOptionsettypeid($type->getName());
		}

		return $object;
	}
}

?>