<div class="menu">
{defun name="menurecursion" list=$menu level="1" rlast="true"}
	{assign var=i value=0}
	{foreach from=$list item=page}
		{assign var=i value=$i+1}	
		{math assign=padding equation="10+x*5" x=$level}
		{if isset($page.subpages)}
			{assign var=togglerlevel value=toggler_$level}
		{else}
			{assign var=togglerlevel value=""}
		{/if}
		
		{if $i == count($list)}
			{if isset($page.subpages)}
				{assign var=last value="last_withsub"}
				{assign var=tmplast value="false"}
			{else}
				{assign var=last value="last_withoutsub"}
				{if $rlast == "true"}
					{assign var=tmplast value="true"}
				{else}
					{assign var=tmplast value="false"}
				{/if}
			{/if}
			{if $rlast == "true"}
				{assign var=tmplast2 value="true"}
			{else}
				{assign var=tmplast2 value="false}
			{/if}
		{else}
			{assign var=last value=""}
			{assign var=tmplast value="false"}
			{assign var=tmplast2 value="false"}
		{/if}

		{if $tmplast == "true"}
			{assign var=last_last value="last_last"}
		{else}
			{assign var="last_last value=""}
		{/if}
		
		
		<div class="toggler {$togglerlevel} toggler_level_{$level} {$page.status} {$page.status_subpages} {$last} {$last_last}" style="padding-left:{$padding}px;">
			<a href="{pagerequest request=$page.page->getRequest()}">
				{$page.page->getTitle()|wordwrap:26:"<br />\n"}
			</a><div class="menuline">&nbsp;</div>	
		</div>
		{if isset($page.subpages)}
		<div class="content_{$level}">
			{fun name="menurecursion" list=$page.subpages level=$level+1 rlast=$tmplast2}
		</div>
		{/if}
	{/foreach}
{/defun}
</div>