{if count($menu) > 1}
<div class="hor_menu"><span class="container">
	{foreach from=$menu item=page name=hormenu}
		<span class="hor_menu_span {$page.status}">
			<span><a href="{pagerequest request=$page.page->getRequest()}">{$page.page->getTitle()}</a></span>
		</span>
		{if !$smarty.foreach.hormenu.last}
			&nbsp;|&nbsp;
		{/if}
{/foreach}
</span>
</div>
{/if}