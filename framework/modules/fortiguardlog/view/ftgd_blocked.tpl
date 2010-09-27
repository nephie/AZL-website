<h1>Fortiguard logs: Blocked</h1>
<div class="headerline">&nbsp;</div>
{include file="mygrid.tpl" grid=$blocked columns="array('time' => array('column' => 'time', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M:%S\"' , 'width' => '106px'),'Gebruiker' => 'user','Groep' => 'group','PC ip' => 'sourceip','Host' => 'host','Categorie' => 'cat','URL' => 'url', 'Surf' => 'goto')"}