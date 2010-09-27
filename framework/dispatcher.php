<?php
session_start();
header('Content-Type: text/html; charset=utf-8');

//	Get the classLoader so everything can be loaded on demand
require_once( FRAMEWORK . DS . 'lib' . DS . 'classloader.php');
set_error_handler('errortoexception' , E_ALL);

//setlocale(LC_ALL,'');

/**
 *
 *
 */
class dispatcher {

	private $xajax;

	public function __construct( $route = null){
		//	No more standard error handling, all to exceptions


		//	Make sure xajax can do it's thing
		$this->xajax = new xajax();

		$this->xajax->configure('characterEncoding','utf-8');
		$this->xajax->configure('decodeUTF8Input',true);


		$this->xajax->register(XAJAX_FUNCTION, 'dispatch' , array(
					'onResponseDelay' => 'showLoadingMessage',
					'onComplete' => 'hideLoadingMessage'
		));

		$this->xajax->register(XAJAX_FUNCTION, 'initpage' , array(
					'onComplete' => 'hideLoadingMessage'
		));

		$this->xajax->register(XAJAX_FUNCTION, 'waypoint_handler', array(
					'onResponseDelay' => 'showLoadingMessage',
					'onComplete' => 'hideLoadingMessage'));

		$this->xajax->processRequest();
	}

	private function resolvePage( $pageid , $auth = true ){
		require(FRAMEWORK . DS . 'conf' . DS . 'dispatcher.php');
		require(FRAMEWORK . DS . 'conf' . DS . 'auth.php');

		$currentuser = myauth::getCurrentuser();
		$groups = $currentuser->getGroupid();

		$groupfound = false;
		foreach($groups as $groupname => $groupid){
			if(isset($defaultPageids[$groupname])){
				$groupfound = true;
				$defaultPageid = $defaultPageids[$groupname];
			}
		}

		if(!$groupfound){
			$defaultPageid = $defaultPageids['default'];
		}

		$pageModel = new pageModel;

		try {
			$page = $pageModel->getfromId($pageid);
			if(count($page) == 1){
				$page = $page[0];
			} elseif($pageid != $defaultPageid){
				try {
					$thepage = $this->resolvePage($defaultPageid);
					$pageid = $page->getId();
				}
				catch (Exception $e){
					//error
					throw $e;
				}
			} else {
				throw $e;
			}
		}
		catch (Exception $e){
			if($pageid != $defaultPageid){
				$pageid = $defaultPageid;
				//	Try the default page
				try {
					$page = $this->resolvePage($pageid);
					$pageid = $page->getId();
				}
				catch (Exception $e){
					//error
					throw $e;
				}
			} else {
				throw $e;
			}
		}

		if( $auth && !myacl::isAllowed(myauth::getCurrentuser() , $page , 'view')){
			if(myauth::getCurrentuser()->getId != $defaultUserid && $pageid != $defaultAnonPageid && $pageid != $loginPageid){
				$page = $this->resolvePage($defaultAnonPageid);
				$pageid = $page->getId();
			} else {
				$_SESSION['wantedpage'] = (isset($_GET['pageid'])) ? $_GET['pageid'] : $defaultPageid;
				$pageid = $loginPageid;

				try {
					$page = $this->resolvePage($pageid , false);
				}
				catch (Exception $e){
					throw $e;
				}
			}
		} else {
			if($page->getRedirectid() != '') {
				$page = $this->resolvePage($page->getRedirectid());
				$pageid = $page->getId();
			}
		}

		myauth::setCurrentpageid($page->getId());
		return $page;
	}

	public function run(){

		require(FRAMEWORK . DS . 'conf' . DS . 'dispatcher.php');
		require(FRAMEWORK . DS . 'conf' . DS . 'auth.php');

		$pageid = (isset($_GET['pageid'])) ? $_GET['pageid'] : $defaultPageid;
		$page = $this->resolvePage($pageid);
		$pageid = $page->getId();

			$module_pageModel = new modulepageModel();

			//	-1 equals all pages
			$req = array();
			$req['pageid'] = array(
				'mode' => 'IN',
				'value' => array('-1' , $pageid),
			);

			$order = array(
				'fields' => array('areaid' , 'order'),
				'type' => 'ASC'
			);

			try{
				$modulesForPage = $module_pageModel->get($req,$order);
			}
			catch (Exception $e){
				//error
				echo $e->getMessage();
			}

			$pageview = new ui();

			$xajaxJs = $this->xajax->getJavascript();

			global $debugajax;
			if($debugajax){
				$debugJs = '<script type="text/javascript" src="xajax_js/xajax_debug.js" charset="UTF-8"></script>';
				$pageview->assign('xajax_javascript' , $xajaxJs . "\n" . $debugJs);
			}
			else {
				$pageview->assign('xajax_javascript' , $xajaxJs);
			}


			$output = array();
			$moduleModel = new moduleModel();
			foreach ($modulesForPage as $moduleForPage){
				try{
					$areaModel = new areaModel();
					$area = $areaModel->getfromId($moduleForPage->getAreaid());
					$meh = 1;
					if(count($area) == 1){
						$area = $area[0];
					}
					else {
						throw new Exception('Area could not be loaded');
					}

					$module = $moduleModel->getfromId($moduleForPage->getModuleid());
					if(count($module) == 1){
						$module = $module[0];
					}
					else {
						throw new Exception('Module could not be loaded');
					}

					$controllerName = $module->getName() . 'Controller';
					$actionName = $module->getAction();

					$controller = new $controllerName($module->getPrefix() . '_' . $module->getName());

					foreach ($controller->getAllowedget() as $allowedGetVar){
						if(isset($_GET[$allowedGetVar])){
							$module->addArguments(array($allowedGetVar => $_GET[$allowedGetVar]));
						}
					}

					$output[$area->getName()] .= '<div id="' . $module->getPrefix() . '_' . $module->getName() . '" class="module">' . $controller->$actionName($module->getArguments()) . '</div>';
				}
				catch (Exception $e){
					//	Ok, that didn't work
					$result = createErrorView($e);
					if( $area instanceof areaObject ){
						$output[$area->getName()] .= '<div class="module">' . $result . '</div>';
					}
				}
			}

			//	Add the popup div
			$output['popup'] = '<div id="popupcontainer" style="visibility: hidden; position: absolute;"></div>';

			foreach ($output as $area => $content){
				$pageview->assign($area , $content);
			}


			//	Add the RTE
			$myrte = new myrte();

			$pageview->assign('rteheader',$myrte->getHeader());

			$pageview->display($page->getTemplate());

			//	Init the responses
			$response = responseLib::getInstance();
			$_SESSION['initpageresponse'] = $response;
			//echo '<script type="text/javascript" charset="UTF-8">xajax_initpage();</script>';
	}

}

function dispatch(){
	$arguments = func_get_args();

	$response = responseLib::getInstance();

	$self = array_shift($arguments);
	$controller = array_shift($arguments);
	$action = array_shift($arguments);

	$params = array();
	foreach ($arguments as $arg){
		if(!is_array($arg)){
			$pieces = explode(':' , $arg , 2);
			if(count($pieces) == 2){
				$params[$pieces[0]] = $pieces[1];
			}
			else {
				$params[] = $pieces[0];
			}
		}
		else {
			if(isset($arg['formdata'])){
				$params += $arg['formdata'];
			}
			else {
				$params[] = $arg;
			}
		}
	}

	$fullControllerName = $controller . 'Controller';
	$fullActionName = $action;

	try {
		$theController = new $fullControllerName($self);
		$theController->$fullActionName($params);

		//	If we get here, no errors should have occured. Clear and hide the errorpane
		$response->clear('errorpane' , 'innerHTML');
		$response->assign('errorpane' , 'style.display' , 'none');
	}
	catch (Exception $e){
		//	Ok, that didn't work
		$response->append('errorpane' , 'innerHTML' , createErrorView($e));
		$response->assign('errorpane' , 'style.display' , 'block');
	}

	return $response;
}

function initpage(){
	$response = unserialize(serialize($_SESSION['initpageresponse']));
	unset($_SESSION['initpageresponse']);

	return $response;
}

/**
 *  sample implementation of backbutton and bookmark handler for xajax plugin
 *
 * @param string $sWaypointName
 * @param string $sWaypointData
 */
function waypoint_handler($sWaypointName, $old, $sWaypointData){
	$response = responseLib::getInstance();

	$sWaypointName = urldecode($sWaypointName);
	$old = urldecode($old);

	$oldhashpoints = array();
	$currenthaspoints = array();
	if($old != ''){
		$oldpoints = explode(';',$old);
		if($oldpoints[0] == ''){
			array_shift($oldpoints);
		}

		foreach($oldpoints as $oldpoint){
			$regex = '/(.+)\\((.+)\\)/';
			$matches = array();
			if(preg_match($regex,$oldpoint,$matches)){
				$key = $matches[1];
				$value = decodeWaypointData($matches[2]);

				$oldhashpoints[$key] = $value;
			}
		}
	}

	if ($sWaypointName!='') {
		$waypoints = explode(';',$sWaypointName);
		if($waypoints[0] == ''){
			array_shift($waypoints);
		}

		foreach($waypoints as $waypoint){
			$regex = '/(.+)\\((.+)\\)/';
			$matches = array();
			if(preg_match($regex,$waypoint,$matches)){
				$key = $matches[1];
				$value = decodeWaypointData($matches[2]);

				$currenthaspoints[$key] = $value;

				if($value != $oldhashpoints[$key]){
					list($controllername,$id) = explode('_',$key);
					$controllername .= 'Controller';
					$action = $value['haction'];

					$self = $value['hself'];
					$controller = new $controllername($self);
					$controller->$action($value);
				}

				if($value['haction'] == $oldhashpoints[$key]['haction']){
					//remove from oldhashpoints because it has been dealt with
					unset($oldhashpoints[$key]);
				}
			}
		}
	}

	foreach($oldhashpoints as $key => $value){
		list($controllername,$id) = explode('_',$key);
		$controllername .= 'Controller';

		$self = $value['hself'];
		$controller = new $controllername($self);
		$action = $value['haction'];

		$rc = new ReflectionClass($controllername);
		if($rc->hasMethod('removedwaypoint')){
			$controller->removedwaypoint($key, $action,$value,$currenthaspoints);
		}
	}

	return $response;
}

?>