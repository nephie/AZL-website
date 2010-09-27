<?php

//	Some constants that should be configured
define( 'DS' , '\\');
define( 'BASE_PATH' , 'D:' . DS . 'websites' . DS . 'azl' . DS . 'framework');
define( 'FRAMEWORK' , BASE_PATH );

//	Get the dispatcher
require_once(FRAMEWORK . DS . 'dispatcher.php');

//	And fire it up
try {
	$disp = new dispatcher();
}
catch (Exception $e){
	echo $e->getMessage();
	echo $e->getTrace();
}

$model = new groupModel();

echo '<pre />' . print_r($model->getfromName($_GET['group']),true) . '</pre>';

?>
