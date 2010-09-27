<?php

//	Some constants that should be configured

define( 'REDIRECT' , TRUE);

define( 'DS' , '\\');
define( 'BASE_PATH' , 'D:' . DS . 'websites' . DS . 'azl' . DS . 'framework');
define( 'FRAMEWORK' , BASE_PATH );

switch($_GET['old']){
	case 'portaal': $oldloc = 'http://lokfile1/redir.php?noredir=true';
		break;
	case 'broodjes': $oldloc = 'http://www.azlokeren.be/broodjes/?noredir=true';
		break;
	case '':  $oldloc = 'http://lokfile1/redir.php?noredir=true';
		break;
}



if(REDIRECT){

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

	$currentuser = myauth::getCurrentuser();

	if($currentuser instanceof userObject){
		$groups = $currentuser->getGroupid();

		if(isset($groups['app_maaltijdbestellen_test'])){
			header('Location:http://intranet');
		}
		else {
			header('Location:' . $oldloc);
		}
	}

}
else {
	header('Location:' . $oldloc);
}
?>
