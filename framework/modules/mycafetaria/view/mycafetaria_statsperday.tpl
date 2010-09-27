<h1>Statistieken</h1>
<div class="headerline">&nbsp;</div>
<p>
	{include file="form.tpl" form=$form}
</p>
<p>
<table>
{foreach from=$count item=mealcount key=name}
	<tr>
		<td>
			<strong><a href="#" onClick="{ajaxrequest request=$mealcount.request}">{$name}</a> : </strong>
		</td>
		<td>
			{$mealcount.count}
		</td>
	</tr>
{/foreach}
</table>
</p>
<div id="grid_mealsperday">
</div>