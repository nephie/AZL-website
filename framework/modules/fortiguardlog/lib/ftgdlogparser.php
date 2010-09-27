<?php
class ftgdlogparser {
	public function parseBlocked(){
		require(FRAMEWORK . DS . 'conf' . DS . 'fortiguardlog.php');

		$logs = file($blockedlogfile);

		$model = new ftgdblockedModel();
		$last = $model->getmax('logid');

		foreach ($logs as $log){
			$logpieces = explode(',',$log);

			$logpieces = explode(',',$log);

			$firstpieces = explode(' ',$logpieces[0]);
			$i = 0;
			$j = 0;
			$time = '';
			while($i < 3){
				if($firstpieces[$j] != ''){
					$time .= $firstpieces[$j] . ' ';
					$i++;
				}
				$j++;
			}
			$time = strtotime($time);
			list($null,$logid) = explode('=',$logpieces[10]);
			list($null,$user) = explode('=',$logpieces[11]); $user = str_replace('"','',$user);
			list($null,$group) = explode('=',$logpieces[12]); $group = str_replace('"','',$group);
			list($null,$srcip) = explode('=',$logpieces[13]);
			list($null,$dstip) = explode('=',$logpieces[16]);
			list($null,$cat) = explode('=',$logpieces[22]); $cat = str_replace('"','',$cat);
			list($null,$host) = explode('=',$logpieces[23]); $host = str_replace('"','',$host);
			list($null,$url) = explode('=',$logpieces[25]); $url = str_replace('"','',$url);

			if($logid > $last){
				$logobject = new ftgdblockedObject();

				$logobject->setLogid($logid);
				$logobject->setUser($user);
				$logobject->setGroup($group);
				$logobject->setSourceip($srcip);
				$logobject->setDestip($dstip);
				$logobject->setCat($cat);
				$logobject->setHost($host);
				$logobject->setUrl($url);
				$logobject->setTime($time);

				$time = date("d/m/Y - H:i:s",$time);
				echo "Time: $time, Log id: $logid, user: $user, group: $group, source ip: $srcip, destination ip: $dstip, url: $host$url, Categorie: $cat\n<br />";

				$model->save($logobject);
			}
		}
	}

public function parseAllowed(){
		require(FRAMEWORK . DS . 'conf' . DS . 'fortiguardlog.php');

		$logs = file($allowedlogfile);
//echo '<pre>' . print_r($logs,true) . '</pre>';253164284 / 253185130
		$model = new ftgdallowedModel();
		$last = $model->getmax('logid');

		$logids = array();

		foreach ($logs as $log){
			$logpieces = explode(',',$log);

			$firstpieces = explode(' ',$logpieces[0]);
			$i = 0;
			$j = 0;
			$time = '';
			while($i < 3){
				if($firstpieces[$j] != ''){
					$time .= $firstpieces[$j] . ' ';
					$i++;
				}
				$j++;
			}
			$time = strtotime($time);
			list($null,$logid) = explode('=',$logpieces[10]);
			list($null,$user) = explode('=',$logpieces[11]); $user = str_replace('"','',$user);
			list($null,$group) = explode('=',$logpieces[12]); $group = str_replace('"','',$group);
			list($null,$srcip) = explode('=',$logpieces[13]);
			list($null,$dstip) = explode('=',$logpieces[16]);
			list($null,$cat) = explode('=',$logpieces[22]); $cat = str_replace('"','',$cat);
			list($null,$host) = explode('=',$logpieces[23]); $host = str_replace('"','',$host);
			list($null,$url) = explode('=',$logpieces[25]); $url = str_replace('"','',$url);

			if($logid > $last && !isset($logids[$logid])){

				//little test to weed out dupes
				$testcondtime = array('time' => array('mode' => '>', 'value' => $time - 5));
				$testcondsrcip = array('sourceip' => array('mode' => '=', 'value' => $srcip));
				$testcondhost = array('host' => array('mode' => '=', 'value' => $host));
				$test = $model->get(array('AND' => array($testcondtime,$testcondsrcip,$testcondhost)));

				if(count($test) == 0){

					$logobject = new ftgdblockedObject();

					$logobject->setLogid($logid);
					$logobject->setUser($user);
					$logobject->setGroup($group);
					$logobject->setSourceip($srcip);
					$logobject->setDestip($dstip);
					$logobject->setCat($cat);
					$logobject->setHost($host);
					$logobject->setUrl($url);
					$logobject->setTime($time);

					$time = date("d/m/Y - H:i:s",$time);
					echo "Time: $time, Log id: $logid, user: $user, group: $group, source ip: $srcip, destination ip: $dstip, url: $host$url, Categorie: $cat\n<br />";

					$model->save($logobject);
				}

				$logids[$logid] = $logid;
			}
		}

	}
}
?>