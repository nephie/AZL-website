<?php

//	Load all configuration files
$conffiles = scandir(FRAMEWORK . DS . 'conf');
foreach ($conffiles as $conffile) {
	//	No hidden stuff and no dirs
	if(substr($conffile ,0 , 1) != '.' && !is_dir(FRAMEWORK . DS . 'conf' . DS . $conffile)){
		require_once(FRAMEWORK . DS . 'conf' . DS . $conffile);
	}
}

//	Some files just need to be loaded
require_once(FRAMEWORK . DS . 'conf' . DS . 'debug.php');
require_once(FRAMEWORK . DS . 'lib' . DS . 'errortoexception.php');
require_once(FRAMEWORK . DS . 'lib' . DS . 'xajax' . DS . 'xajax_core' . DS . 'xajaxAIO.inc.php');
require_once(FRAMEWORK . DS . 'lib' . DS . 'dhtmlHistory.php');

/**
 * Auto include of the file needed for a class.
 *
 * @param string $class
 */
function __autoload($class)
{
	list ($classname , $type) = explode('_' , strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $class)) , 2);

	$found = false;

	if($type == ''){
		$type = 'lib';
	}

	if(file_exists((FRAMEWORK . DS . $type . DS .$class . '.php'))) {
		require_once(FRAMEWORK . DS . $type . DS .$class . '.php');
		$found = true;
	}
	else
	{
		$modules = scandir(FRAMEWORK . DS . 'modules' . DS);
		foreach ($modules as $moduleDir){
			if($moduleDir != '.' && $moduleDir != '..'){
				if(file_exists(FRAMEWORK . DS . 'modules' . DS . $moduleDir . DS . 'types.php')){
					require(FRAMEWORK . DS . 'modules' . DS . $moduleDir . DS . 'types.php');
					if(isset($types[$type])){
						$type = $types[$type];
					}
				}
				if(file_exists(FRAMEWORK . DS . 'modules' . DS . $moduleDir . DS . $type . DS . $class . '.php')) {
					require_once(FRAMEWORK . DS . 'modules' . DS . $moduleDir . DS . $type . DS . $class . '.php');
					$found = true;
					break;
				}
			}
		}
	}

	if(!$found) {
		$exception =  new classException('Class ' . $class . ' not found');
		$trace = $exception->getTrace();
		$exception->setFile($trace[1]['file']);
		$exception->setLine($trace[1]['line']);
		throw $exception;
	}
}

?>