<?php

$admingroup = 'dienst_informatica';
$baseusergroup = 'CN=dienst_alle,OU=Diensten,OU=Groepen,DC=stadskliniek,DC=lokeren,DC=be';
$baseusergroupocmw = 'CN=GG_OCMW_EVERYONE,OU=AZL Broodjes,OU=My Business,DC=ocmw,DC=lokeren,DC=local';

foreach(glob(FRAMEWORK . DS . 'conf' . DS . 'myacl' . DS . '*.php') as $file){
	include($file);
}

?>