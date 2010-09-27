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

$domain = (isset($_GET['domain']))?$_GET['domain']:1;

$model = new userModel($domain);

echo '<pre />' . print_r($model->getfromName($_GET['user']),true) . '</pre>';
try{
	echo '<pre />' . print_r($model->getfromId($_GET['user']),true) . '</pre>';
}
catch(Exception $e){}
echo '<pre />' . print_r($model->getfromEid($_GET['user']),true) . '</pre>';

?>
