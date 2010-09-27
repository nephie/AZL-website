<?php
class processedmoduleModel extends moduleModel {
	protected $table = 'app_module';

	protected function fillObject($data){
		$object = parent::fillObject($data);

		$args = $object->getArguments();

		$module = $object->getName();
		$moduleaction = $object->getAction();

		$contr = new mymoduleadminController();

		$options = $contr->getOptions($module,$moduleaction);

		foreach($args as $key => $value){

			if(isset($options[$key][$value])) {
				$value = $options[$key][$value];
			}

			$arguments .= '<strong>' . $key . ': </strong> ' . $value . '<br />';
		}

		$object->setArguments($arguments);

		return $object;
	}
}
?>