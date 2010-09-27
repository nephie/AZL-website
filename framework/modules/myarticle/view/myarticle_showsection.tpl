{foreach from=$articles item=article}
	{if $article instanceof myarticleversionObject}
	<div id="article_{$article->getId()}" class="article">
		<h1>{$article->getTitle()}</h1>
		<div class="headerline">&nbsp;</div>
		{$article->getContent()}
		<p>
			&nbsp;
		</p>
	</div>
	{/if}
{/foreach}