<p>
	Bent u zeker dat u de link met dit artikel wilt verwijderen?
</p>
<p>
	<table>
		<tr>
			<td>
				<strong>Werktitel: </strong>
			</td>
			<td>
				{$link->getAlias()}
			</td>
		</tr>
		<tr>
			<td>
				<strong>Aangemaakt door: </strong>
			</td>
			<td>
				{$link->getArticleauthorname()}
			</td>
		</tr>
		<tr>
			<td>
				<strong>Aanmaakdatum: </strong>
			</td>
			<td>
				{$link->getArticlecreationdate()|date_format:"%H:%M - %d/%m/%Y"}
			</td>
		</tr>
	</table>
</p>
<p>
	&nbsp;
</p>