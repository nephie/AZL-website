<table>
	<tr>
		<td>
			<strong>Starttijd:</strong>
		</td>
		<td>
			{$blackout->getBlackoutperiodstart()|date_format:"%H:%M"}
		</td>
	</tr>
	<tr>
		<td>
			<strong>Stoptijd:</strong>
		</td>
		<td>
			{$blackout->getBlackoutperiodend()|date_format:"%H:%M"}
		</td>
	</tr>
	<tr>
		<td>
			<strong>Trigger tijd:</strong>
		</td>
		<td>
			{$blackout->getTriggertime()}
		</td>
	</tr>
	<tr>
		<td>
			<strong>Dagen:</strong>
		</td>
		<td>
			{$blackout->getDays()}
		</td>
	</tr>
</table>
<br />
{if is_array($meals)}
	De volgende maaltijden zijn gekoppeld:
	<blockquote>
	<ul>
	{foreach from=$meals item=meal}
		<li>{$meal->getName()}</li>
	{/foreach}
	</ul>
	</blockquote>
{else}
	Er zijn geen maaltijden gekoppeld.
{/if}
<br />
<br />