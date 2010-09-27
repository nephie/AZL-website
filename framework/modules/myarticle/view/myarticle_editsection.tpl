{include file="form.tpl" form=$form}
{if $grid instanceof mygrid}
<h1>Gelinkte artikels</h1>
<div class="headerline">&nbsp;</div>
{include file="mygrid.tpl" grid=$grid columns="array('Werktitel' => 'alias', 'Aangemaakt door' => 'articleauthorname', 'Aanmaakdatum' => array('column' => 'articlecreationdate','modifier' => 'date_format:\"%H:%M - %d/%m/%Y\"'))"}
{/if}
<h1>Rechten voor sectie {$section->getName()}</h1>
<div class="headerline">&nbsp;</div>
<p>
<div id="acllist_section">{$acllist}</div>
</p>