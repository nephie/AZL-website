{if $article instanceof myarticleObject}
<div id="article_{$article->getArticleid()}" class="article">
	<h1>{$article->getTitle()}</h1>
	<div class="headerline">&nbsp;</div>
	<div style="position: absolute; right: 0px; top: 0px;"><a href="#" onClick="{ajaxrequest request=$editrequest}">Edit</a></div>
	{$article->getContent()}
	<p>
		&nbsp;
	</p>
</div>
{/if}