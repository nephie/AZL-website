<div style="position: relative;">
<h1>DirectoryWatcher: Fouten</h1>
<div style="position: absolute; right: 0px; top: 0px;">Ongecontroleerd: {$unprocessedcount} (<a href="#" onclick="{ajaxrequest request=$processrequest}">nu controleren</a>)</div>
<div class="headerline">&nbsp;</div>
{include file="mygrid.tpl" grid=$errorgrid columns="array('Path' => 'path', 'Aantal bestanden' => 'numfiles','Laatst aangepast' => array('column' => 'lastfiletime', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"' , 'width' => '130px'), 'Oudste bestand' => array('column' => 'oldestfiletime', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"' , 'width' => '130px'), 'Bestaat' => 'exists', 'Meldingstijd' => array('column' => 'reporttime', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"' , 'width' => '130px'), 'Status' => 'status')"}
<h1>DirectoryWatcher: Alles</h1>
<div class="headerline">&nbsp;</div>
{include file="mygrid.tpl" grid=$grid columns="array('Path' => 'path', 'Aantal bestanden' => 'numfiles','Laatst aangepast' => array('column' => 'lastfiletime', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"' , 'width' => '130px'), 'Oudste bestand' => array('column' => 'oldestfiletime', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"' , 'width' => '130px'), 'Bestaat' => 'exists', 'Meldingstijd' => array('column' => 'reporttime', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"' , 'width' => '130px'), 'Status' => 'status')"}
<h1>Standaard Treshold</h1>
<div class="headerline">&nbsp;</div>
{include file="mygrid.tpl" grid=$deftresholdgrid columns="array('Aantal bestanden' => 'numfiles','Laatst aangepast' => 'lastfiletime', 'Oudste bestand' => 'oldestfiletime', 'Bestaat' => 'exists', 'Mail' => 'mail', 'Mail naar' => 'mailto')"}
</div>