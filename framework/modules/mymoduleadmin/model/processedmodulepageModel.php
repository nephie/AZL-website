<?php
class processedmodulepageModel extends modulepageModel {
	protected $table = 'app_modulepage';


	protected function fillObject($data){
		$object = parent::fillObject($data);

		$areamodel = new areaModel();
		$areas = $areamodel->getfromId($object->getAreaid());

		if(count($areas) == 1){
			$object->setAreaname($areas[0]->getName());
		}

		$pagemodel = new pageModel();
		$pages = $pagemodel->getfromId($object->getPageid());

		if(count($pages) == 1){
			$object->setPagename($pages[0]->getTitle());
		}

		$modulemodel = new moduleModel();
		$modules = $modulemodel->getfromId($object->getModuleid());

		if(count($modules) == 1){
			$module = $modules[0];

			$object->setModuletitle($module->getTitle());
			$object->setModulename($module->getName());
			$object->setModuleaction($module->getAction());

			$args = $module->getArguments();
			$contr = new mymoduleadminController();
			$options = $contr->getOptions($module->getName(),$module->getAction());
			foreach($args as $key => $value){

				if(isset($options[$key][$value])) {
					$value = $options[$key][$value];
				}

				$arguments .= '<strong>' . $key . ': </strong> ' . $value . '<br />';
			}

			$object->setModuleargs($arguments);
		}

		return $object;

	}
}
?>