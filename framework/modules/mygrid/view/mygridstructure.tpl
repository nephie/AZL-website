{string2array string=$columns var="columnsarray"}
{if count($columnsarray) > 0}
	{assign var="hack" value=$grid->setColumn($columnsarray)}
{/if}
<table class="grid">
	{include file="mygridhead.tpl"}
	{include file="mygridrows.tpl"}
	{include file="mygridfoot.tpl"}
</table>