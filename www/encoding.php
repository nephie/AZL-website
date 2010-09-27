<?php

define( 'DS' , '\\');
define( 'BASE_PATH' , 'C:' . DS . 'inetpub' . DS . 'wwwroot' . DS . 'intranet');
define( 'FRAMEWORK' , BASE_PATH . DS . 'intranet_framework' );

echo '
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></meta>
</head>
<body>
€
	<form method="POST">
		<input type="text" name="test" />
		<input type="submit" />
	</form>
';

echo '<pre>' . print_r($_POST,true) . '</pre>';


require(FRAMEWORK . DS . 'conf' . DS . 'datastore.php');

require(FRAMEWORK . DS . 'lib' . DS . 'adodb5' . DS . 'adodb.inc.php');

$config = $datastore['default'];



$query = "SELECT * FROM test";



$db = ADONewConnection('mssql_n');

$db->debug = true;

$db->Connect($config['host'],$config['user'], $config['password'],$config['db']);

if(isset($_POST['test'])){
	$ins = "INSERT INTO test (test) values ('" . $_POST['test'] . "')";

	if($db->execute($ins) === false){echo "meh";};
}

	$rs2 = $db->Execute($query);

	echo '<pre>' . print_r($rs2->getRows(),true) . '</pre>';

?>