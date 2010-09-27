<div id="ticketcontainer" class="extracontainer" style="position: relative;"></div>
{if is_array($tickets)}
	{foreach from=$tickets item=ticketlist key=dienst}
		<h1>Meldingen van dienst {$dienst}</h1>
		<div class="headerline">&nbsp;</div>
		{include file="mygrid.tpl" grid=$ticketlist columns="array('Titel' => 'titel', 'Aan' => 'toname', 'Contact' => 'contact', 'Status' => 'status', 'Gemeld op' => array('column' => 'time', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"'), 'volgnummer' => 'id')"}
	{/foreach}
{/if}