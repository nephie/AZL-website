<?php

function smarty_function_ajaxform($params, &$smarty){
	$form = $params['form'];
	$self = $smarty->_tpl_vars['self'];

	if($params['notfinal'] == true){
		$request = $form->getPhasedrequest();
	}
	else{
		$request = $form->getRequest();
	}

	if(isset($params['field'])){
		$request->setParameter(array_merge($request->getParameter(),array('__field__' => $params['field'])));
	}

	if(isset($params['abort'])){
		$request->setParameter(array_merge($request->getParameter(),array('abort' => $params['abort'])));
	}

	require( FRAMEWORK . DS . 'conf' . DS . 'xajax.php');

	$function = ($ownDispatchFunction) ? 'my_dispatch' : 'xajax_dispatch';

	$string = "$function( '$self' , '" . $request->getController() . "' , '" . $request->getAction() . "'";

	if(count($request->getParameter()) > 0){
		$params = array();
		foreach ($request->getParameter() as $key => $parameter) {
			$params[] = $key . ':' . $parameter;
		}
		$string .= " , '" . implode("' , '" , $params) . "'";
	}

	$string .= ', { formdata: xajax.getFormValues(\'' . $form->getId() . '\') } );return false;';

	return $string;
}

?>