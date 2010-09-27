<h1>Beheer tijden</h1>
<div class="headerline">&nbsp;</div>
<p>
	Onderstaande tijden geven aan wanneer er <strong>niet</strong> kan besteld worden. Deze tijden moeten aan de maaltijden gekoppeld worden.
</p>
{include file="mygrid.tpl" grid=$times columns="array('Starttijd' => array('column' => 'blackoutperiodstart', 'modifier' => 'date_format:\"%H:%M\"' ), 'Stoptijd' => array('column' => 'blackoutperiodend', 'modifier' => 'date_format:\"%H:%M\"' ), 'Trigger tijd' => 'triggertime' , 'Dagen' => 'days')"}