<?php

//	Some constants that should be configured
define( 'DS' , '\\');
define( 'BASE_PATH' , 'D:' . DS . 'websites' . DS . 'azl' . DS . 'framework');
define( 'FRAMEWORK' , BASE_PATH );
//	Get the dispatcher
require_once(FRAMEWORK . DS . 'dispatcher.php');

require_once(FRAMEWORK . DS . 'lib' . DS . 'jpgraph' . DS . 'src' . DS . 'jpgraph.php');
require_once (FRAMEWORK . DS . 'lib' . DS . 'jpgraph' . DS . 'src' . DS . 'jpgraph_line.php');
require_once (FRAMEWORK . DS . 'lib' . DS . 'jpgraph' . DS . 'src' . DS . 'jpgraph_date.php');
require_once (FRAMEWORK . DS . 'lib' . DS . 'jpgraph' . DS . 'src' . DS . 'jpgraph_regstat.php');
require_once (FRAMEWORK . DS . 'lib' . DS . 'jpgraph' . DS . 'src' . DS . 'jpgraph_plotline.php');
require_once (FRAMEWORK . DS . 'lib' . DS . 'jpgraph' . DS . 'src' . DS . 'jpgraph_plotband.php');


$dienst = $_GET['dienst'];
$starttime = $_GET['starttime'];
$endtime = $_GET['endtime'];

$dienstmodel = new keukendienstModel();
if($dienst != '_all_'){
	$dienstobj = $dienstmodel->getfromDienstnr($dienst);
	if(count($dienstobj) > 0){
		$dienstobj = $dienstobj[0];

		$aantalbedden = $dienstobj->getAantalbedden();
		$naam = $dienstobj->getName();

		$model = new bedbezModel();
		$cond = array('time' => array('mode' => 'BETWEEN', 'topvalue' => $endtime, 'value' => $starttime));
		$condve = array('ve' => array('mode' => '=', 'value' => $dienstobj->getDienstnr()));

		$objects = $model->get(array('AND' => array($cond,$condve)),array('fields' => array('time'),'type' => 'ASC'));

		foreach($objects as $obj){
			$ydata[] = $obj->getAantal();
			$xdata[] = $obj->getTime();
		}
	}
}
else {
	$naam = 'AZ Lokeren';

	$dienstobjs = $dienstmodel->get();

	foreach($dienstobjs as $dienstobj){
		$aantalbedden += $dienstobj->getAantalbedden();
	}
		$model = new bedbezModel();
		$cond = array('time' => array('mode' => 'BETWEEN', 'topvalue' => $endtime, 'value' => $starttime));

		$objects = $model->get(array('AND' => array($cond)),array('fields' => array('time'),'type' => 'ASC'));

		foreach($objects as $obj){
			$ydata[$obj->getTime()] += $obj->getAantal();
			$xdata[$obj->getTime()] = $obj->getTime();
		}


	$xdata = array_values($xdata);
	$ydata = array_values($ydata);
}


	$width = 600; $height = 400;


	// Create a graph instance
	$graph = new Graph($width,$height);
	$graph->img->SetMargin(80,30,55,90);
	$graph->img->SetAntiAliasing(true);
	$graph->SetClipping(true);

	// Specify what scale we want to use
	$maxscale = ($aantalbedden > max($ydata)) ? $aantalbedden + 3 : max($ydata) + 3;
	$graph->SetScale('datlin',0,$maxscale ,$starttime,$endtime);

	// Setup a title for the graph
	$title = 'Bedbezetting ' . $naam;
	$graph->title->Set($title);
	$graph->title->SetFont(FF_ARIAL,FS_BOLD,14);

	$subtitle = date('d/m/Y',$starttime) . ' - ' . date('d/m/Y',$endtime);
	$graph->subtitle->Set($subtitle);
	$graph->subtitle->SetFont(FF_ARIAL,FS_BOLD,12);

	// Setup titles and X-axis labels
	$graph->xaxis->title->Set('');
	$graph->xaxis->SetLabelAngle(45);
	$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);


	// Setup Y-axis title
	$graph->yaxis->title->Set('');
	$graph->yaxis->SetFont(FF_ARIAL);


	// Create the linear plot

	if($starttime < strtotime('-13 months',$endtime)){
		$spline = new Spline($xdata,$ydata);

		list($nxdata,$nydata) = $spline->get(100);
		$lineplot=new LinePlot($nydata,$nxdata);
	}
	else {
		$lineplot=new LinePlot($ydata,$xdata);
	}

//	echo '<pre>';
//	$i = 0;
//	foreach ($xdata as $date){
//		echo $i . ' | ' . $date . ' | ' . date('j-m-Y',$date) . ' | ' . $ydata[$i] . "\n";
//		$i++;
//	}
//
//
//	echo '</pre>';

	$lineplot->SetColor('blue');
	$lineplot->SetFillColor("blue@0.8");

	if($starttime > strtotime('-13 months',$endtime)){
		$lineplot->SetStepStyle(true);
	}


	$orangeline = new PlotLine(HORIZONTAL, $aantalbedden * 0.8,'orange',1);
	$redline = new PlotLine(HORIZONTAL, $aantalbedden * 0.9,'red',1);
	$maxline = new PlotLine(HORIZONTAL, $aantalbedden,'black',1);

	// Add the plot to the graph
	$graph->Add($lineplot);
	$graph->AddLine($orangeline);
	$graph->AddLine($redline);
	$graph->AddLine($maxline);
	
	if($starttime < strtotime('-6 months',$endtime)){
		// Trend
		$prec = floor(count($ydata) / 10);
		//echo $prec . '<br />';
		$avg = array();
		for($i = 0; $i < count($ydata); $i++){
			$tmp = 0;
			for($j = 1; $j < $prec; $j++){
				$tmp += $ydata[$i - $j];
				$tmp += $ydata[$i + $j];
			}
				$tmp += $ydata[$i];
			//echo $tmp . '<br />';
			$div = ($prec * 2) + 1;
			if($i - $prec < 0){
				$div = $prec + $i + 1;
			}
			if($i + $prec > count($ydata)){
				$div = (count($ydata) - 1) - $i + $prec + 1 ;
			}
			$avg[$i] = $tmp / $div;
		}

		$trendline = new LinePlot($avg,$xdata);
		$trendline->SetWeight(2);
		$graph->AddLine($trendline);
	}

	//Weekends

	if($starttime > strtotime('-13 months',$endtime)){
		for($i = 0; $i < count($xdata); $i++){
			$rtime = $xdata[$i];
			//get start time of this day
			$start = mktime(0,0,0,date('n',$rtime),date('j',$rtime),date('Y',$rtime));

			if(date('w',$rtime) == 6){
				$end = $start + (60*60*24*2);
				$band = new PlotBand(VERTICAL,BAND_SOLID,$start, $end,'lightgray@0.7');
				$band->ShowFrame(false);
				$graph->add($band);
			}

			$year = date('Y',$rtime);

			$holidays['nieuwjaarsdag'] = strtotime('1 January ' . $year);
			$holidays['nieuwjaarsdag2'] = strtotime('2 January ' . $year);
			$holidays['pasen'] = easter_date($year);
			$holidays['paasmaandag'] = strtotime('+1 day',$holidays['pasen']);
			$holidays['olhhemelvaart'] = strtotime('+39 days',$holidays['pasen']);
			$holidays['olhhemelvaart2'] = strtotime('+1day',$holidays['olhhemelvaart']);
			$holidays['pinksteren'] = strtotime('+49 days',$holidays['pasen']);
			$holidays['pinkstermaandag'] = strtotime('+1 day',$holidays['pinksteren']);
			$holidays['guldensporenslag'] = strtotime('11 July ' . $year);
			$holidays['nationale_feestdag'] = strtotime('21 July ' . $year);
			$holidays['allerheiligen'] = strtotime('1 November ' . $year);
			$holidays['allerzielen'] = strtotime('2 November ' . $year);
			$holidays['wapenstilstand'] = strtotime('11 November ' . $year);
			$holidays['dynastie'] = strtotime('15 November ' . $year);
			$holidays['kerstdag'] = strtotime('25 December ' . $year);
			$holidays['kerstdag2'] = strtotime('26 December ' . $year);
			$holidays['kerstdagmin1'] = strtotime('24 December ' . $year);
			$holidays['december31'] = strtotime('31 December ' . $year);

			$holidaytimes = array_flip($holidays);

			if(isset($holidaytimes[$start])){
				$end = $start + (60*60*24);
				$band = new PlotBand(VERTICAL,BAND_SOLID,$start, $end,'#66FFCC@0.8');
				$band->ShowFrame(false);
				$graph->add($band);
			}
		}
	}

	// Display the graph
	$graph->Stroke();

?>