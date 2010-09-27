{include file="form.tpl" form=$aliasform}

<h1>Versies</h1>
<div class="headerline">&nbsp;</div>
{include file="mygrid.tpl" grid=$grid columns="array('Status' => 'state', 'Titel' => 'title', 'Aangemaakt door' => 'authorname' , 'Aanmaakdatum' => array('column' => 'creationdate','modifier' => 'date_format:\"%H:%M - %d/%m/%Y\"') )"}

{if $sectiongrid instanceof mygrid}
<h1>Gelinkte secties</h1>
<div class="headerline">&nbsp;</div>
{include file="mygrid.tpl" grid=$sectiongrid columns="array('Sectie' => 'sectionname')"}
{/if}
{if $acllist != ''}
<h1>Rechten voor artikel {$article->getAlias()}</h1>
<div class="headerline">&nbsp;</div>
<p>
<div id="acllist_article">{$acllist}</div>
</p>
{/if}