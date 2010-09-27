<p>
	Bent u zeker dat u deze treshold wilt verwijderen?
</p>
<p>
	<table>
		<tr>
			<td>
				<strong>Path: </strong>
			</td>
			<td>
				{$treshold->getPath()}
			</td>
		</tr>
		<tr>
			<td>
				<strong>Aantal bestanden: </strong>
			</td>
			<td>
				{$treshold->getNumfiles()}
			</td>
		</tr>
		<tr>
			<td>
				<strong>Laatst aangepast: </strong>
			</td>
			<td>
				{$treshold->getLastfiletime()}
			</td>
		</tr>
		<tr>
			<td>
				<strong>Oudste bestand: </strong>
			</td>
			<td>
				{$treshold->getOldestfiletime()}
			</td>
		</tr>
		<tr>
			<td>
				<strong>Bestaat: </strong>
			</td>
			<td>
				{$treshold->getExists()}
			</td>
		</tr>
		<tr>
			<td>
				<strong>Mail: </strong>
			</td>
			<td>
				{$treshold->getMail()}
			</td>
		</tr>
		<tr>
			<td>
				<strong>Mail naar: </strong>
			</td>
			<td>
				{$treshold->getMailto()}
			</td>
		</tr>
	</table>
</p>
<br />
<br />