<h1>Beheer maaltijden</h1>
<div class="headerline">&nbsp;</div>
{include file="mygrid.tpl" grid=$meals columns="array('Maaltijd' => 'name', 'Type' => 'mealtypeid', 'Prijs' => array('column' => 'price', 'modifier' => 'string_format:\"€%.2f\"'),'Prijs externen' => array('column' => 'price2', 'modifier' => 'string_format:\"€%.2f\"'))"}
