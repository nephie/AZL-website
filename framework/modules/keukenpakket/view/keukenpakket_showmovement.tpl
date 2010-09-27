<h1>Keukenpakket: {$title}</h1>
<div class="headerline">&nbsp;</div>
{include file="form.tpl" form=$form}
{if $type == 'verplaatsing'}
	{include file="mygrid.tpl" grid=$grid columns="array('Tijd' => array('column' => 'time', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M:%S\"' , 'width' => '130px'), 'Kamer' => 'kamer', 'Bed' => 'bed', 'Vorige kamer' => 'pkamer', 'Vorig bed' => 'pbed', 'Voornaam' => 'voornaam', 'Achternaam' => 'achternaam', 'Patiëntnr' => 'patientnr', 'Dossiernr' => 'dossiernr')"}
{else}
	{include file="mygrid.tpl" grid=$grid columns="array('Tijd' => array('column' => 'time', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M:%S\"' , 'width' => '130px'), 'Kamer' => 'kamer', 'Bed' => 'bed', 'Voornaam' => 'voornaam', 'Achternaam' => 'achternaam', 'Patiëntnr' => 'patientnr', 'Dossiernr' => 'dossiernr')"}
{/if}