<h1>Warme maaltijden van vandaag</h1>
<div class="headerline">&nbsp;</div>
{include file="mygrid.tpl" grid=$grid columns="array('Besteld voor' => 'user', 'Afhaling' => array('column' => 'uur', 'modifier' => 'date_format:\"%H:%M\"' , 'width' => '120px'), 'Maaltijd' => 'meal')"}