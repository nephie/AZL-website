<h1>Beheer opties</h1>
<div class="headerline">&nbsp;</div>
{include file="mygrid.tpl" grid=$options columns="array('Optie' => 'name', 'Prijs' => array('column' => 'price', 'modifier' => 'string_format:\"€%.2f\"'),'Prijs externen' => array('column' => 'price2', 'modifier' => 'string_format:\"€%.2f\"'))"}