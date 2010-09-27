<?php
	require_once( FRAMEWORK . DS . 'lib' . DS . 'ui' . DS . 'Smarty' . DS . 'Smarty.class.php' );

	class ui extends Smarty {
		public function __construct($controller = null){

			$this->cache_dir = FRAMEWORK . DS . 'lib' . DS . 'ui' . DS . 'Smarty' . DS . 'cache';
			$this->compile_dir = FRAMEWORK . DS . 'lib' . DS . 'ui' . DS . 'Smarty' . DS . 'templates_c';
			$this->cache_dir = FRAMEWORK . DS . 'lib' . DS . 'ui' . DS . 'Smarty' . DS . 'configs';

			if($controller != null && !is_object($controller)){
				$controller = new $controller();
			}

			if(is_object($controller)){

				if($controller instanceof controller ){
					$this->assign('self' , $controller->getSelf());
				}

				$reflector = new ReflectionClass($controller);
				$controllerFile = $reflector->getFileName();
				$dirPieces = explode(DS,$controllerFile);

				// Strip down the path until we come to a directory with a view directory in it (which should be the module directory)
				while(!file_exists(implode(DS , $dirPieces) . DS . 'view')){
					array_pop($dirPieces);
				}
				$moduleDir = implode(DS , $dirPieces);

				$dirPieces[] = 'view';

				//	Include the common template dir as well ... put it first in the array to make it have priority
				$this->template_dir = array( FRAMEWORK . DS . 'view' , implode(DS,$dirPieces));

				//	Include configured dependancies to look for templates last
				if(file_exists($moduleDir . DS . 'dependancy.php')){
					require($moduleDir . DS . 'dependancy.php');
					foreach ($dependancy as $extraModule){
						//	Dir structure should be: /base_path/framework/modules/modulename/controller/controllerfile
						$this->template_dir[] = FRAMEWORK . DS . 'modules' . DS . $extraModule . DS . 'view';
					}
				}
				if(file_exists(FRAMEWORK . DS . 'conf' . DS . 'globaldependancy.php')){
					require(FRAMEWORK . DS . 'conf' . DS . 'globaldependancy.php');
					foreach ($globaldependancy as $extraModule){
						//	Dir structure should be: /base_path/framework/modules/modulename/controller/controllerfile
						$this->template_dir[] = FRAMEWORK . DS . 'modules' . DS . $extraModule . DS . 'view';
					}
				}
			}
			else {
				$this->template_dir = FRAMEWORK . DS . 'view';
			}

		}

	}
?>