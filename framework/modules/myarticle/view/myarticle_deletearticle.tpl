<p>
	Bent u zeker dat u dit artikel wilt verwijderen?
</p>
<p>
	<table>
		<tr>
			<td>
				<strong>Werktitel: </strong>
			</td>
			<td>
				{$article->getAlias()}
			</td>
		</tr>
		<tr>
			<td>
				<strong>Aangemaakt door: </strong>
			</td>
			<td>
				{$article->getAuthorname()}
			</td>
		</tr>
		<tr>
			<td>
				<strong>Aangemaakdatum: </strong>
			</td>
			<td>
				{$article->getCreationdate()|date_format:"%H:%M - %d/%m/%Y"}
			</td>
		</tr>
	</table>
</p>
<p>
	{if $linked != ''}
		Deze sectie momenteel aan de volgende secties gelinked:
		<ul>
		{foreach from=$linked item=sectie}
			<li>
				<strong>Sectie: </strong> {$sectie->getName()}
			</li>
		{/foreach}
		</ul>
	{else}
		Er zijn op dit moment geen secties aan dit artikel gelinked.
	{/if}
</p>
<p>
	&nbsp;
</p>