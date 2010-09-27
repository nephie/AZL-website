<h1>
	Pagina beheer
	{if $currentpage instanceof pageObject}
	: 	{foreach from=$path item=page name=path}
			<a href="#" onclick="{ajaxrequest request=$page.request}">{$page.page->getTitle()}
			{if !$smarty.foreach.path.last} / {/if}</a>
		{/foreach}
	{/if}
</h1>
<div class="headerline">&nbsp;</div>

{if $currentpage instanceof pageObject}
<h2>Titel aanpassen</h2>
<p>
	{include file="form.tpl" form=$titleform}
</p>
{/if}

<h2>Onderliggende pagina's</h2>
<p>
{include file="mygrid.tpl" grid=$grid columns="array('Titel' => 'title')"}
</p>

{if $currentpage instanceof pageObject}
<h2>Rechten</h2>
<p>
	{$acl}
</p>
{/if}

<h1>Gekoppelde modules</h1>
<div class="headerline">&nbsp;</div>
{foreach from=$modules item=area key=areaname}
<h2>{$areaname}</h2>
<p>
{include file="mygrid.tpl" grid=$area columns="array('Titel' => 'moduletitle', 'Module' => 'modulename', 'Actie' => 'moduleaction', 'Argumenten' => 'moduleargs')"}
</p>
{/foreach}