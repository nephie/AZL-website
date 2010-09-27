<?php

function smarty_function_pagerequest($params, &$smarty){
	$request = $params['request'];

	$paramstring = '';
	if(count($request->getParameter()) > 0){
		$params = array();
		foreach ($request->getParameter() as $key => $parameter) {

			$params[] = $key . '=' . $parameter;

		}
		$paramstring .= "&" . implode("&" , $params);
	}

	$string = $_SERVER['PHP_SELF'] . '?pageid=' . $request->getPageid() . $paramstring;

	return $string;
}

?>