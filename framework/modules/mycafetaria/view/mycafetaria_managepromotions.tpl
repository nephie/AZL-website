<h1>Promoties beheren</h1>
<div class="headerline">&nbsp;</div>
{include file="mygrid.tpl" grid=$promotions columns="array('Type' => 'name','Start' => array('column' => 'starttime', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"'),'Einde' => array('column' => 'stoptime', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"'))"}

<h1>Promotie teksten</h1>
<div class="headerline">&nbsp;</div>
{include file="mygrid.tpl" grid=$articles columns="array('Werktitel' => 'alias', 'Aangemaakt door' => 'articleauthorname', 'Aanmaakdatum' => array('column' => 'articlecreationdate','modifier' => 'date_format:\"%H:%M - %d/%m/%Y\"'))"}