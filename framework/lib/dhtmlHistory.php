<?php

	class dhtmlHistoryPlugin extends xajaxResponsePlugin
	{
		var $sDefer;
		var $sJavascriptURI;
		var $bInlineScript;
		var $sWaypointFunctionName;

		function dhtmlHistoryPlugin()
		{
			$this->sDefer = '';
			$this->sJavascriptURI = '';
			$this->bInlineScript = true;
			$this->sWaypointFunctionName='xajax_waypoint_handler';
		}

		function configure($sName, $mValue)
		{
			if ('javascript URI' == $sName) {
				$this->sJavascriptURI = $mValue;
			} else if ('WaypointFunctionName' == $sName) {
				$this->sWaypointFunctionName = $mValue;
			}
/*
 // does only work with inline script so far; todo enable script loading using redo timeout stuff
			} else if ('scriptDeferral' == $sName) {
				if (true === $mValue || false === $mValue) {
					if ($mValue) $this->sDefer = 'defer ';
					else $this->sDefer = '';
				}
			} else if ('inlineScript' == $sName) {
				if (true === $mValue || false === $mValue)
					$this->bInlineScript = $mValue;
*/
		}

	/*
		Function: generateClientScript

		Called by the <xajaxPluginManager> during the script generation phase.  This
		will either inline the script or insert a script tag which references the
		<tableUpdater.js> file based on the value of the <clsTableUpdater->bInlineScript>
		configuration option.
	*/
		function generateClientScript(){
			$JSONVer=2005;  // 2005|2007
			if ($this->bInlineScript)
			{
				echo "\n<script type='text/javascript' " . $this->sDefer . " charset='UTF-8'>\n";
				echo "/* <![CDATA[ */\n";
				//include(dirname(__FILE__) . "/json$JSONVer.js");
				echo "\n\n";
				include(dirname(__FILE__) . '/rsh.js');
				if ($JSONVer==2007)
					echo "\n\nwindow.dhtmlHistory.create(); \n";
				else
					echo "window.dhtmlHistory.create({toJSON: JSON.encode,fromJSON: JSON.decode});\n";
				echo "\n\nfunction dhtmlHistoryInit() { \n";

				echo "\tdhtmlHistory.initialize();\n";
				echo "\tdhtmlHistory.addListener(".$this->sWaypointFunctionName.");\n";
				echo "\tvar s=dhtmlHistory.getCurrentLocation(); // check for bookmark hash value\n";
				echo "\tif (s.length>0)\n";
				echo "\t\t".$this->sWaypointFunctionName."(s,null);\n";
				echo "\t}\n";

				echo "/* ]]> */\n";
				echo "</script>\n";
			} else {
				echo "\n<script type='text/javascript' src='" . $this->sJavascriptURI . "dhtmlHistory.js' " . $this->sDefer . "charset='UTF-8'>\n";
			}
		}

		function getName() {
			return "dhtmlHistoryPlugin";
		}

	   	function addWaypoint($sWaypointName, $aWaypointData) {
	   		$sWaypointData = base64_encode(serialize($aWaypointData));
	   		$this->objResponse->script("dhtmlHistory.add('$sWaypointName($sWaypointData)','')");
			//$this->addCommand(array('cmd'=>'js'),"dhtmlHistory.add('$sWaypointName','$sWaypointData')" );
	   	}

	}

	// register rsh plugin
	$pluginManager =& xajaxPluginManager::getInstance();
	$pluginManager->registerPlugin(new dhtmlHistoryPlugin());

	/**
	 * xajax 0.5 plugin for backbutton and bookmark
	 *
	 * helper functions
	 *
	 */

   	/**
   	 * Adds a waypoint into the backbutton history
   	 *
   	 * @param unknown_type $objResponse
   	 * @param string $sWaypointName
   	 * @param mixed $sWaypointData
   	 */
   	function dhtmlHistoryAdd(&$objResponse, $sWaypointName, $sWaypointData)
   	{
   		//$objResponse->plugin('dhtmlHistoryPlugin', 'addWaypoint', $sWaypointName, $sWaypointData);	 //php4 and php5
		$objResponse->dhtmlHistoryPlugin->addWaypoint($sWaypointName, $sWaypointData);	    	//php5 only
   	}

	/**
	 * decode the stringified waypoint data (see addWaypoint() also)
	 */
   	function decodeWaypointData($sWaypointData)
   	{
   		return (is_string($sWaypointData))?(unserialize(base64_decode($sWaypointData))):'';
   	}
?>