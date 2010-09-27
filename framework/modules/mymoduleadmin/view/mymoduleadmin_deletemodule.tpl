<p>
	Bent u zeker dat u deze module wilt verwijderen?
</p>
<p>
	<table>
		<tr>
			<td>
				<strong>Titel: </strong>
			</td>
			<td>
				{$module->getTitle()}
			</td>
		</tr>
		<tr>
			<td>
				<strong>Module: </strong>
			</td>
			<td>
				{$module->getName()}
			</td>
		</tr>
		<tr>
			<td>
				<strong>Actie: </strong>
			</td>
			<td>
				{$module->getAction()}
			</td>
		</tr>
		<tr>
			<td>
				<strong>Argumenten: </strong>
			</td>
			<td>
				{$module->getArguments()}
			</td>
		</tr>
	</table>
</p>
<br />
<p>
{if count($pages) > 0}
	Deze module is aan de volgende pagina's gelinked:
	<ul>
		{foreach from=$pages item="page"}
			<li>{$page->getTitle()}</li>
		{/foreach}
	</ul>
{else}
	Deze module is aan geen enkele pagina gelinked.
{/if}
</p>
<br />