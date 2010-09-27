<?php

class highlightresponse extends xajaxResponse {

	public $historyRecall = false;

	function assign($sTarget,$sAttribute,$sData, $highlight = false){

		parent::assign($sTarget,$sAttribute,$sData);

		if($highlight){
			$this->highlight($sTarget);
		}
	}

	function append($sTarget,$sAttribute,$sData, $highlight = false){

		$randomid = rand();

		parent::append($sTarget,$sAttribute,'<span id="' . $randomid . '"></span>');

		parent::append($randomid,$sAttribute,$sData);

		if($highlight){
			$this->highlight($randomid);
		}
	}

	function highlight($sTarget){
		$this->script("highlight('$sTarget');");
	}

	function addWaypoint($hcontroller, $haction, $hid, $hsWaypointData)
   	{
   		if(!$this->historyRecall){
   			$hsWaypointName = $hcontroller . '_' . $hid;
   			$hsWaypointData['history'] = 'history';
   			$hsWaypointData['haction'] = $haction;

   			$trace = debug_backtrace();
			if(is_object($trace[1]['object'])){
				$hsWaypointData['hself'] = $trace[1]['object']->getSelf();
			}

   			$this->dhtmlHistoryPlugin->addWaypoint($hsWaypointName, $hsWaypointData);
   		}
   	}
}
?>