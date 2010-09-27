<?php

//	Some constants that should be configured
define( 'DS' , '\\');
define( 'BASE_PATH' , 'D:' . DS . 'websites' . DS . 'azl' . DS . 'framework');
define( 'FRAMEWORK' , BASE_PATH );

//	Get the dispatcher
require_once(FRAMEWORK . DS . 'dispatcher.php');

//	And fire it up
try {
	//$disp = new dispatcher();
}
catch (Exception $e){
	echo $e->getMessage();
	echo $e->getTrace();
}

	$parser = new ftgdlogparser();

	$parser->parseBlocked();
	$parser->parseAllowed();

	//clear old logs
	$old = 60*60*24*7*4;	// 4 weeks
	$cond = array('time' => array('mode' => '<','value' => time() - $old));

	$amodel = new ftgdallowedModel();
	$bmodel = new ftgdblockedModel();

	$amodel->delete($cond);
	$bmodel->delete($cond);
	
?>
