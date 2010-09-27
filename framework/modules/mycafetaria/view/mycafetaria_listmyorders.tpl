<h1>Reeds besteld</h1>
<div class="headerline">&nbsp;</div>
<p>
	Selecteer een persoon van wie u de bestellingen wenst te zien.
</p>
{include file="form.tpl" form=$form}

{if isset($orders)}
	<h1>De bestellingen voor vandaag van {$name}</h1>
	<div class="headerline">&nbsp;</div>

	{if count($orders) == 0}
		Er is nog niets besteld voor vandaag.
	{else}
		{include file="mygrid.tpl" grid=$myorderlisttoday columns="array('Besteld op' => array('column' => 'orderuur', 'modifier' => 'date_format:\"%H:%M - %d/%m/%Y\"' , 'width' => '120px'),'Afhaling' => array('column' => 'uur', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"' , 'width' => '120px'),'Besteld door' => 'orderuser', 'Maaltijd' => 'meal',  'Prijs' => array('column' => 'price', 'modifier' => 'string_format:\"€%.2f\"' , 'width' => '40px'))"}
		<table>
		{foreach from=$orders item=order}
			<tr>
				<td>
					&nbsp;
				</td>
			</tr><tr>
				<td>
					<strong>Uur van afhaling: </strong>
				</td>
				<td>
					{$order->getUur()|date_format:"%H:%M"}
				</td>
			</tr>
			<tr>
				<td>
					<strong>Maaltijd: </strong>
				</td>
				<td>
					{$order->getMeal()}
				</td>
			</tr>
			<tr>
				<td>
					<strong>Prijs: </strong>
				</td>
				<td>
					€{$order->getPrice()|string_format:"%.2f"}
				</td>
			</tr><tr>
				<td>
					&nbsp;
				</td>
			</tr>
		{/foreach}
		</table>
	{/if}
{/if}
{if isset($myorderlist)}
	<p>
		&nbsp;
	</p>
	<h1>Al de bestellingen van {$name}</h1>
	<div class="headerline">&nbsp;</div>
	{include file="mygrid.tpl" grid=$myorderlist columns="array('Besteld op' => array('column' => 'orderuur', 'modifier' => 'date_format:\"%H:%M - %d/%m/%Y\"' , 'width' => '120px'),'Afhaling' => array('column' => 'uur', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"' , 'width' => '120px'),'Besteld door' => 'orderuser', 'Maaltijd' => 'meal',  'Prijs' => array('column' => 'price', 'modifier' => 'string_format:\"€%.2f\"' , 'width' => '40px'))"}
{/if}