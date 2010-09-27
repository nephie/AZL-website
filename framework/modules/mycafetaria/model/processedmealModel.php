<?php

class processedmealModel extends mealModel {

	protected $table = 'app_meal';

	protected function fillObject($data){
		$object = parent::fillObject($data);

		$model = new mealtypeModel();

		$type = $model->getfromId($object->getMealtypeid());

		if(count($type) == 1){
			$type = $type[0];

			$object->setMealtypeid($type->getName());
		}

		return $object;
	}

}

?>