<h1>Details voor <a href="file:///{$current->getPath()}">{$current->getPath()}</a></h1>
<div style="position: absolute; right: 0px; top: 0px;"><a href="#" onClick="{ajaxrequest request=$closerequest}">Sluiten</a></div>
<div class="headerline">&nbsp;</div>
<p>
{include file="mygrid.tpl" grid="$history" columns="array('Aantal bestanden' => 'numfiles','Laatst aangepast' => array('column' => 'lastfiletime', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"' , 'width' => '130px'), 'Oudste bestand' => array('column' => 'oldestfiletime', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"' , 'width' => '130px'), 'Bestaat' => 'exists', 'Meldingstijd' => array('column' => 'reporttime', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"' , 'width' => '130px'), 'Status' => 'status')"}
</p>
<h1>Treshold voor {$current->getPath()}</h1>
<div class="headerline">&nbsp;</div>
<p>
{include file="mygrid.tpl" grid=$tresholdgrid columns="array('path' => 'path', 'Aantal bestanden' => 'numfiles','Laatst aangepast' => 'lastfiletime', 'Oudste bestand' => 'oldestfiletime', 'Bestaat' => 'exists', 'Mail' => 'mail', 'Mail naar' => 'mailto')"}
</p>