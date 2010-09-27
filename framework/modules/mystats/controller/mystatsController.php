<?php

function sort_validatie($a,$b){
	if($a['validerendeArtsNaam'] == $b['validerendeArtsNaam']){
		return 0;
	}
	return ($a['validerendeArtsNaam'] < $b['validerendeArtsNaam'])? -1: 1;
}

function mystats_doktersort($a,$b){
	if($a['naam'] == $b['naam']){
		return 0;
	}
	return ($a['naam'] < $b['naam'])? -1: 1;
}

class mystatsController extends controller {
	public function showbedbez($parameters = array()){
		$view = new ui($this);

		$dienstmodel = new keukendienstModel();
//		$kamermodel = new keukenkamerModel();
		$model = new keukenpatientModel();

		$diensten = $dienstmodel->get();
		$adiensten = array();

		foreach($diensten as $dienst){
			//if(myacl::isAllowed(myauth::getCurrentuser(),$dienst,'countstats')){
				$adiensten[$dienst->getId()] = $dienst;
			//}
		}

		$startofday = mktime(00, 00, 00, date('m'), date('j'), date('Y'));
		$endoftoday = mktime(00, 00, 00, date('m'), date('j') + 1, date('Y')) -1;
		$startofyesterday = mktime(00, 00, 00, date('m'), date('j') - 1, date('Y'));
		$endofyesterday = mktime(00, 00, 00, date('m'), date('j'), date('Y')) -1;
		$startofmonth = mktime(00, 00, 00, date('m'), 01, date('Y'));
		$endofmonth = mktime(00, 00, 00, date('m') + 1 , 01, date('Y')) - 1;
		$startoflastmonth = mktime(00, 00, 00, date('m')- 1, 01, date('Y'));
		$endoflastmonth = $startofmonth - 1;
		$startofyear = mktime(00, 00, 00, 01, 01, date('Y'));
		$endofyear = mktime(00, 00, 00, 01, 01, date('Y') + 1) - 1;
		$startoflastyear = mktime(00, 00, 00, 01, 01, date('Y') -1);
		$endoflastyear = mktime(00, 00, 00, 01, 01, date('Y')) -1;


		$outputdiensten = array();
		$totalcond = array();
		$totalmax = 0;
		foreach($adiensten as $id => $dienst){
			//$kamercond = array();
			//$kamers =  $kamermodel->getfromDienstid($id);

//			foreach($kamers as $kamer){
//				$kamercond[] = array('kamer' => array('mode' => '=','value' => $kamer->getKamernr()));
//			}
//
//			$totalcond = array_merge($totalcond,$kamercond);
//			if(count($kamercond) > 0){
//				$kamercond = array('OR' => $kamercond);
//			}
//			else {
//				//Geen kamers, lege lijst voorzien
//				$kamercond = array('kamer' => array('mode' => '=','value' => '-1'));
//			}

			$cond = array('ve' => array('mode' => '=', 'value' => $dienst->getDienstnr()));

			$count = $model->getcount($cond);

			$outputdiensten[$id]['dienst'] = $dienst;
			$outputdiensten[$id]['count'] = $count;
			$outputdiensten[$id]['graphtoday'] = new ajaxrequest('mystats','showbedbezgraph',array('dienst' => $dienst->getDienstnr(), 'starttime' => $startofday, 'endtime' => $endoftoday));
			$outputdiensten[$id]['graphyesterday'] = new ajaxrequest('mystats','showbedbezgraph',array('dienst' => $dienst->getDienstnr(), 'starttime' => $startofyesterday, 'endtime' => $endofyesterday));
			$outputdiensten[$id]['graphtm'] = new ajaxrequest('mystats','showbedbezgraph',array('dienst' => $dienst->getDienstnr(), 'starttime' => $startofmonth, 'endtime' => $endofmonth));
			$outputdiensten[$id]['graphlm'] = new ajaxrequest('mystats','showbedbezgraph',array('dienst' => $dienst->getDienstnr(), 'starttime' => $startoflastmonth, 'endtime' => $endoflastmonth));
			$outputdiensten[$id]['graphty'] = new ajaxrequest('mystats','showbedbezgraph',array('dienst' => $dienst->getDienstnr(), 'starttime' => $startofyear, 'endtime' => $endofyear));
			$outputdiensten[$id]['graphly'] = new ajaxrequest('mystats','showbedbezgraph',array('dienst' => $dienst->getDienstnr(), 'starttime' => $startoflastyear, 'endtime' => $endoflastyear));

			$totalmax += $dienst->getAantalbedden();
		}

		sort($outputdiensten);

//		if(count($totalcond) > 0){
//			$totalcond = array('OR' => $totalcond);
//		}
//		else {
//			//Geen kamers, lege lijst voorzien
//			$totalcond = array('kamer' => array('mode' => '=','value' => '-1'));
//		}

		$totalgraph['graphtoday'] = new ajaxrequest('mystats','showbedbezgraph',array('dienst' => '_all_', 'starttime' => $startofday, 'endtime' => $endoftoday));
		$totalgraph['graphyesterday'] = new ajaxrequest('mystats','showbedbezgraph',array('dienst' => '_all_', 'starttime' => $startofyesterday, 'endtime' => $endofyesterday));
		$totalgraph['graphtm'] = new ajaxrequest('mystats','showbedbezgraph',array('dienst' => '_all_', 'starttime' => $startofmonth, 'endtime' => $endofmonth));
		$totalgraph['graphlm'] = new ajaxrequest('mystats','showbedbezgraph',array('dienst' => '_all_', 'starttime' => $startoflastmonth, 'endtime' => $endoflastmonth));
		$totalgraph['graphty'] = new ajaxrequest('mystats','showbedbezgraph',array('dienst' => '_all_', 'starttime' => $startofyear, 'endtime' => $endofyear));
		$totalgraph['graphly'] = new ajaxrequest('mystats','showbedbezgraph',array('dienst' => '_all_', 'starttime' => $startoflastyear, 'endtime' => $endoflastyear));


		$totalcond = array('ve' => array('mode' => '<>', 'value' => ''));
		$total = $model->getcount($totalcond);
		$view->assign('total',$total);
		$view->assign('totalmax',$totalmax);

		$view->assign('diensten',$outputdiensten);
		$view->assign('totalgraph',$totalgraph);

		$view->assign('printrequest', new ajaxrequest('mystats','printbedbez'));

		$this->response->assign($this->self,'innerHTML',$view->fetch('mystats_showbedbez.tpl'));
	}

	public function printbedbez($parameters = array()){
		$view = new ui($this);

		$dienstmodel = new keukendienstModel();
//		$kamermodel = new keukenkamerModel();
		$model = new keukenpatientModel();

		$diensten = $dienstmodel->get();
		$adiensten = array();

		foreach($diensten as $dienst){
			//if(myacl::isAllowed(myauth::getCurrentuser(),$dienst,'countstats')){
				$adiensten[$dienst->getId()] = $dienst;
			//}
		}


		$outputdiensten = array();
		$totalcond = array();
		$totalmax = 0;
		foreach($adiensten as $id => $dienst){
			//$kamercond = array();
			//$kamers =  $kamermodel->getfromDienstid($id);

//			foreach($kamers as $kamer){
//				$kamercond[] = array('kamer' => array('mode' => '=','value' => $kamer->getKamernr()));
//			}
//
//			$totalcond = array_merge($totalcond,$kamercond);
//			if(count($kamercond) > 0){
//				$kamercond = array('OR' => $kamercond);
//			}
//			else {
//				//Geen kamers, lege lijst voorzien
//				$kamercond = array('kamer' => array('mode' => '=','value' => '-1'));
//			}

			$cond = array('ve' => array('mode' => '=', 'value' => $dienst->getDienstnr()));

			$count = $model->getcount($cond);

			$outputdiensten[$id]['dienst'] = $dienst;
			$outputdiensten[$id]['count'] = $count;

			$totalmax += $dienst->getAantalbedden();
		}

		sort($outputdiensten);

//		if(count($totalcond) > 0){
//			$totalcond = array('OR' => $totalcond);
//		}
//		else {
//			//Geen kamers, lege lijst voorzien
//			$totalcond = array('kamer' => array('mode' => '=','value' => '-1'));
//		}

		$totalcond = array('ve' => array('mode' => '<>', 'value' => ''));
		$total = $model->getcount($totalcond);
		$view->assign('total',$total);
		$view->assign('totalmax',$totalmax);

		$view->assign('diensten',$outputdiensten);

		$this->response->assign($this->self,'innerHTML',$view->fetch('mystats_printbedbez.tpl'));
	}

	public function closebedbezgraph($parameters = array()){
		$this->response->assign('bedbezgraph','innerHTML', '' );
	}

	public function showbedbezgraph($parameters = array()){
		$view = new ui($this);

		$starttime = (isset($parameters['starttime']))? $parameters['starttime'] : strtotime('-1 months');
		$endtime = (isset($parameters['endtime']))? $parameters['endtime'] : time();
		$dienst = $parameters['dienst'];

		$form = new form($parameters);
		$form->addField(new hiddenField('dienst',$parameters['dienst']));

		$dienstmodel = new keukendienstModel();

		$diensten = $dienstmodel->get();
		$adiensten = array();

		$select = new selectField('dienst','Dienst',array('required'));
		$select->addOption(new selectoptionField('Alles','_all_',($dienst == '_all_')?true:false));
		foreach($diensten as $dienstobj){
			//if(myacl::isAllowed(myauth::getCurrentuser(),$dienst,'countstats')){
				$select->addOption(new selectoptionField($dienstobj->getName(),$dienstobj->getDienstnr(),($dienst == $dienstobj->getDienstnr())?true:false));
			//}
		}
		$form->addField($select);
		$form->addField(new datepickerField('starttime','Startdatum',false,$starttime,array('required')));
		$form->addField(new datepickerField('endtime','Einddatum',false,$endtime,array('required')));

		if($form->validate()){
			$view->assign('dienst',$parameters['dienst']);
			$view->assign('starttime',$starttime);
			$view->assign('endtime',$endtime);

			$view->assign('closerequest' , new ajaxrequest('mystats','closebedbezgraph'));

			$view->assign('form',$form);

			$this->response->assign('bedbezgraph','innerHTML',$view->fetch('mystats_showbedbezgraph.tpl'));
		}
		elseif(!$form->isSent()){
			$view->assign('dienst',$parameters['dienst']);
			$view->assign('starttime',$starttime);
			$view->assign('endtime',$endtime);

			$view->assign('closerequest' , new ajaxrequest('mystats','closebedbezgraph'));

			$view->assign('form',$form);

			$this->response->assign('bedbezgraph','innerHTML',$view->fetch('mystats_showbedbezgraph.tpl'));
		}
		else {

		}
	}

	public function showbedbezbydoc($parameters = array()){
		$view = new ui($this);

		$dienstmodel = new keukendienstModel();
		$model = new keukenpatientModel();

		$diensten = $dienstmodel->get();
		$adiensten = array();

		foreach($diensten as $dienst){
			//if(myacl::isAllowed(myauth::getCurrentuser(),$dienst,'countstats')){
				$adiensten[$dienst->getId()] = $dienst->getDienstnr();
			//}
		}
		asort($adiensten);
		$cond = array('ve' => array('mode' => 'IN', 'value' => $adiensten));
		$all = $model->get($cond);

		$dokters = array();
		$ve = array();
		foreach($all as $pat){
			$dokters[$pat->getDokterognummer()]['naam'] = $pat->getDokternaam();
			$dokters[$pat->getDokterognummer()][$pat->getVe()]++;
			$dokters[$pat->getDokterognummer()]['all']++;
			$ve[$pat->getVe()]++;
			$ve['all']++;
		}

		uasort($dokters,mystats_doktersort);

		$view->assign('diensten',$adiensten);
		$view->assign('stats',$dokters);
		$view->assign('ve',$ve);

		$this->response->assign($this->self,'innerHTML',$view->fetch('mystats_showbedbezbydoc.tpl'));
	}

	public function showvalidatiesnelheid($parameters = array()){
		$view = new ui($this);


		$startthistime = strtotime('-1 months');
		$startthis = date("m/j/Y",$startthistime);
		$view->assign('startthis',$startthistime);
		$condthis = array('creatiedatum' => array('mode' => '>','value' => $startthis));

		$sql_validated = "
SELECT     	validerendeArtsNummer, validerendeArtsNaam, COUNT(voorschriftID) AS total, AVG(validatieArtsSnelheid) AS avg, MIN(validatieArtsSnelheid)
           	AS min, MAX(validatieArtsSnelheid) AS max
FROM       	dbo.ValidatieSnelheid
WHERE     	(creatieDatum > '$startthis') AND (validerendeArtsNummer IS NOT NULL)
GROUP BY  	validerendeArtsNummer, validerendeArtsNaam
ORDER BY 	validerendeArtsNaam
		";

		$sql_notvalidated = "
SELECT     	verantwoordelijkeArtsNummer, verantwoordelijkeArtsNaam, COUNT(voorschriftID) AS total
FROM     	ValidatieSnelheid
WHERE     	(creatieDatum > '$startthis') AND (validerendeArtsNummer IS NULL)
GROUP BY 	verantwoordelijkeArtsNummer, verantwoordelijkeArtsNaam
ORDER BY 	verantwoordelijkeArtsNaam
		";

		require(FRAMEWORK . DS . 'conf' . DS . 'datastore.php');

		$config = $datastore['mb'];

		$con = NewADOConnection($config['protocol']);
		if(!$con){
			throw new connectException('Could not initialize the ADO class');
		}

		if($con->Connect($config['host'], $config['user'], $config['password'], $config['db'])){
			$con->setFetchMode(ADODB_FETCH_ASSOC);
		}
		else {
			throw new connectException('Could not connect to the datastore: ' . $con->ErrorMsg());
		}

		$rs_validated = $con->getAssoc($sql_validated);
		$rs_notvalidated = $con->getAssoc($sql_notvalidated);


		foreach ($rs_validated as $id => $row){
			$avg = $row['avg'];
			$top = $avg + ($avg * 0.45);
			$bottom = $avg - ($avg * 0.45);

			$sql_normalized = "
SELECT     	validerendeArtsNummer, validerendeArtsNaam, COUNT(voorschriftID) AS total, AVG(validatieArtsSnelheid) AS avg, MIN(validatieArtsSnelheid)
           	AS min, MAX(validatieArtsSnelheid) AS max
FROM       	dbo.ValidatieSnelheid
WHERE     	(creatieDatum > '$startthis') AND (validerendeArtsNummer IS NOT NULL)
AND			(validerendeArtsNummer = '$id')
AND			(validatieArtsSnelheid BETWEEN $bottom AND $top)
GROUP BY  	validerendeArtsNummer, validerendeArtsNaam
ORDER BY 	validerendeArtsNaam
			";

			$rs_normalized = $con->getAssoc($sql_normalized);
			$rs_validated[$id]['normavg'] = $rs_normalized[$id]['avg'];
		}


		$rs = $rs_validated;

		foreach($rs_notvalidated as $id => $row){
			$rs[$id]['notvalidated'] = $row['total'];
			if( $rs[$id]['validerendeArtsNaam'] == ''){
				$rs[$id]['validerendeArtsNaam'] = ($row['verantwoordelijkeArtsNaam'] != '') ? $row['verantwoordelijkeArtsNaam']: $id;
			}
		}

		uasort($rs,sort_validatie);

		$view->assign('avg',$rs);

		$this->response->assign($this->self,'innerHTML',$view->fetch('mystats_showvalidatiesnelheid.tpl'));
	}
}
?>