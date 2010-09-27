<?php

class processedmealblackoutModel extends mealblackoutModel {

	protected $table = 'app_mealblackout';


	protected function fillObject($data){

		$object = parent::fillObject($data);

		$today = strtotime('today');

		$object->setBlackoutperiodstart($today + $object->getBlackoutperiodstart());
		$object->setBlackoutperiodend($today + $object->getBlackoutperiodend());

		$trigger = $object->getTriggertime();

		if($trigger == -1){
			$trigger = 'Altijd';
		}
		else {
			$trigger = date("H:i",$today + $trigger);
		}

		$object->setTriggertime($trigger);

		$days = $object->getDays();

		switch($days) {
			case 1 : $days = 'Weekdagen';
				break;
			case 2 : $days = 'Weekend';
				break;
			case 3 : $days = 'Alle dagen';
				break;
		}

		$object->setDays($days);

		return $object;
	}

}

?>