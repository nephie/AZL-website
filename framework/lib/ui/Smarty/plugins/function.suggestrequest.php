<?php

function smarty_function_suggestrequest($params, &$smarty){
	$request = $params['request'];
	unset($params['request']);

	$self = $smarty->_tpl_vars['self'];

	$theparams = array();
	foreach($params as $key => $parameter){
		$theparams[] = $key . ':' . $parameter;
	}


	require(FRAMEWORK . DS . 'conf' . DS . 'xajax.php');

	$function = ($ownDispatchFunction) ? 'my_dispatch' : 'xajax_dispatch';

	$string = "$function( '$self' , '" . $request->getController() . "' , '" . $request->getAction() . "'";


	if(count($request->getParameter()) > 0){

		foreach ($request->getParameter() as $key => $parameter) {
			$theparams[] = $key . ':' . $parameter;
		}
		$string .= " , '" . implode("' , '" , $theparams ) . "'";
	}

	$string .= ", 'id:' + this.id , 'value:' + this.value )";

	return $string;
}

?>