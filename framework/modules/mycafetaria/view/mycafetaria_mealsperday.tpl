{if isset($grid)}
	<p>
		&nbsp;
	</p>
	<h1>{$meal}</h1>
	<div class="headerline">&nbsp;</div>
	{include file="mygrid.tpl" grid=$grid columns="array('Afhaling' => array('column' => 'uur', 'modifier' => 'date_format:\"%H:%M - %d/%m/%Y\"' , 'width' => '120px'),'Besteld op' => array('column' => 'orderuur', 'modifier' => 'date_format:\"%H:%M - %d/%m/%Y\"' , 'width' => '120px'),'Besteld door' => 'orderuser', 'Besteld voor' => 'user', 'Maaltijd' => 'meal',  'Prijs' => array('column' => 'price', 'modifier' => 'string_format:\"€%.2f\"' , 'width' => '60px'), 'Geprint' => 'printed')"}
{/if}