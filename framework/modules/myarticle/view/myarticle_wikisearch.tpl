<div style="position: relative;">
<h1>Wiki search</h1>
<div style="position: absolute; right: 0px; top: 0px;"><a href="#" onClick="{ajaxrequest request=$closerequest}">Sluiten</a></div>
<div class="headerline">&nbsp;</div>
{include file="mygrid.tpl" grid=$grid columns="array('Werktitel' => 'alias', 'Aangemaakt door' => 'articleauthorname', 'Aanmaakdatum' => array('column' => 'articlecreationdate','modifier' => 'date_format:\"%H:%M - %d/%m/%Y\"'))"}
</div>