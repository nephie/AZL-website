<?php

class processedmealorderModel extends mealorderModel {

	protected $table = 'app_mealorder';

	protected function fillObject($data){
		$object = parent::fillObject($data);

		$mealorderoptionsetModel = new mealorderoptionsetModel();
		$mealorderoptionModel = new mealorderoptionModel();

		$optionsets = $mealorderoptionsetModel->getfromMealorderid($object->getId());

		$opties = '';
		foreach($optionsets as $optionset){
			$opties .= '<strong>' . $optionset->getOptionset() . ': </strong> ';

			$options = $mealorderoptionModel->getfromMealorderoptionsetid($optionset->getId());

			if($optionset->getOptionsettype() == 3){
				if($options[0] instanceof mealorderoptionObject ){
					if($options[0]->isSelected()){
						$opties .= 'Ja';
					}
					else {
						$opties .= 'Nee';
					}
				}
			}
			else {
				foreach($options as $option){
					if($option->isSelected()){
						$opties .= $option->getOption() . ', ';
					}
				}

				$opties = substr($opties,0,-2);
			}

			$opties .=  '<br />';
		}

		//$object->setOption($opties);

		$object->setMeal('<strong>' . $object->getMeal() . '</strong><br /><br />' . $opties);

		$object->setPrinted(($object->getPrinted())?'Ja':'Nee');

		return $object;
	}
}
?>