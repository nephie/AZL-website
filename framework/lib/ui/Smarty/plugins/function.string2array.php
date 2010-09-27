<?php

function processString2Array($string)
{
	$arr = array();

	$matches = array();

	if(preg_match('/(array\()(.*)(\))/' , $string, $matches)){

		eval("\$arr = $string;");
	}

	return $arr;
}

function smarty_function_string2array($params, &$smarty)
{
	 if (empty($params['var'])) {
        $smarty->trigger_error("assign: missing 'var' parameter");
        return;
    }

	if (empty($params['string'])) {
        $smarty->trigger_error("assign: missing 'string' parameter");
        return;
    }

	$arr = processString2Array($params['string']);

    $smarty->assign($params['var'], $arr);
}


?>