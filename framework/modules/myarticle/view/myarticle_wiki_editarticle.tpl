<div style="position: relative;">
<h1>Artikel aanpassen</h1>
<div style="position: absolute; right: 0px; top: 0px;"><a href="#" onClick="{ajaxrequest request=$closerequest}">Sluiten</a></div>
<div class="headerline">&nbsp;</div>
{include file="form.tpl" form=$aliasform}

<h1>Versies</h1>
<div class="headerline">&nbsp;</div>
{include file="mygrid.tpl" grid=$grid columns="array('Status' => 'state', 'Titel' => 'title', 'Aangemaakt door' => 'authorname' , 'Aanmaakdatum' => array('column' => 'creationdate','modifier' => 'date_format:\"%H:%M - %d/%m/%Y\"') )"}

</div>