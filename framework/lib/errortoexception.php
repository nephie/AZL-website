<?php

function errortoexception($errno, $errstr, $errfile, $errline , $errcontext){

	//	Only throw exceptions for real errors, ... warnings and noticed may be debugged though
	switch ($errno){
    	case E_USER_WARNING:
		case E_USER_NOTICE:
		case E_WARNING:
		case E_NOTICE:
		case E_CORE_WARNING:
		case E_COMPILE_WARNING:
			break;
		case E_USER_ERROR:
		case E_ERROR:
		case E_PARSE:
		case E_CORE_ERROR:
		case E_COMPILE_ERROR:
			$exception = new errortoException($errstr , $errno);
			$exception->setFile($errfile);
			$exception->setLine($errline);

			throw $exception;
   	}
}

function createErrorView($exception){
	$ui = new ui();

	$ui->assign('exceptionid' , uniqid());
	$ui->assign('exception' , $exception);
	$template = (VERBOSE_ERRORS) ? 'detailederror.tpl' : 'shorterror.tpl';

	return $ui->fetch($template);
}

?>