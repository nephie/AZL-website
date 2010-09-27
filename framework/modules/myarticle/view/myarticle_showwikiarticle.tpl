{if $article instanceof myarticleversionObject}
<div id="article_{$article->getId()}" class="article">
	<div id="wikibreadcrumbs_{$sectionid}" class="breadcrumbs">
		{if count($breadcrumbs) > 1}
			{foreach from=$breadcrumbs item=crumb name=crumbs}
				{if $smarty.foreach.crumbs.index >= $smarty.foreach.crumbs.total - 5}
				<a href="#" onclick="{ajaxrequest request=$crumb[0]}">{$crumb[1]}</a>
				{if !$smarty.foreach.crumbs.last}&gt;{/if}
				{/if}
			{/foreach}
		{/if}
	</div>
	<div class="wikisearch" style="position: absolute; right: 0px; top: -10px;">
		{include file="inlineform.tpl" form=$searchform}
	</div>
	<br />
	<div style="position:relative;">
	<h1>{$article->getTitle()}{if $editrequest instanceof ajaxrequest}
	<a  class="wikiedit" href="#" onClick="{ajaxrequest request=$editrequest}"><img src="files/images/edit_gridrow_A.png" title="Aanpassen"/></a>
	{/if}</h1>

	<div class="headerline">&nbsp;</div>
	{$article->getWikicontent($sectionid,$self)}
	</div>
</div>
{/if}