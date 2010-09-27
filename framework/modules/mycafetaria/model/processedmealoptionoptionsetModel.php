<?php

class processedmealoptionoptionsetModel extends mealoptionoptionsetModel {

	protected $table = 'app_mealoptionoptionset';

	protected function fillObject($data){
		$object = parent::fillObject($data);

		$model = new processedmealoptionsetModel();


		$optionset = $model->getfromId($object->getOptionsetid());

		if(count($optionset) == 1){
			$optionset = $optionset[0];

			$object->setOptionset($optionset->getName());
			$object->setOptionsettype($optionset->getOptionsettypeid());
		}

		return $object;
	}
}

?>