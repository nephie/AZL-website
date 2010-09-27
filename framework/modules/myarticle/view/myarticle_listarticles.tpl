<h1>Artikels</h1>
<div class="headerline">&nbsp;</div>
{include file="mygrid.tpl" grid=$grid columns="array('Werktitel' => 'alias', 'Aangemaakt door' => 'authorname', 'Aanmaakdatum' => array('column' => 'creationdate','modifier' => 'date_format:\"%H:%M - %d/%m/%Y\"'))"}
{if $acllist != ''}
<h1>Algemene rechten voor artikels</h1>
<div class="headerline">&nbsp;</div>
<p>
<div id="acllist_listarticles">{$acllist}</div>
</p>
{/if}