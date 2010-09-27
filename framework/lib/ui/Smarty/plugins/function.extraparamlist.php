<?php

function smarty_function_extraparamlist($params, &$smarty){
	$field = $params['field'];

	if(count($field->getExtraparams()) > 0){

		foreach ($field->getExtraparams() as $key => $parameter) {
			$theparams[] = $key . '|' . $parameter;
		}
		$string .= implode("," , $theparams ) ;
	}


	return $string;
}

?>