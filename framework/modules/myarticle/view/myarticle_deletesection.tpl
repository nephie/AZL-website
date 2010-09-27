<p>
	Bent u zeker dat u deze sectie wilt verwijderen?
</p>
<p>
	<strong>Sectie: </strong> {$section->getName()}
</p>
<p>
	{if $linked != ''}
		Deze sectie momenteel aan de volgende artikels gelinked:
		<ul>
		{foreach from=$linked item=artikel}
			<li>
				<strong>Werktitel: </strong> {$artikel->getAlias()}
			</li>
		{/foreach}
		</ul>
	{else}
		Er zijn op dit moment geen artikels aan deze sectie gelinked.
	{/if}
</p>
<p>
	&nbsp;
</p>