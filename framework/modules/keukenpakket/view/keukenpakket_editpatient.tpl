<p>
<table>
	<tr>
		<td>
			<strong>Naam: </strong>&nbsp;
		</td>
		<td>
			{$patient->getVoornaam()} {$patient->getAchternaam()}
		</td>
	</tr>
	<tr>
		<td>
			<strong>Patiëntnummer: </strong>&nbsp;
		</td>
		<td>
			{$patient->getPatientnr()}
		</td>
	</tr>
	<tr>
		<td>
			<strong>Huidig dossiernummer: </strong>&nbsp;
		</td>
		<td>
			{$patient->getCurrentdossiernr()}
		</td>
	</tr>
	<tr>
		<td>
			<strong>Geboortedatum: </strong>&nbsp;
		</td>
		<td>
			{$patient->getGeboortedatum()|date_format:"%d/%m/%Y"}
		</td>
	</tr>
	<tr>
		<td>
			<strong>Geslacht: </strong>&nbsp;
		</td>
		<td>
			{$patient->getGeslacht()}
		</td>
	</tr>
	<tr>
		<td>
			&nbsp;
		</td>
		<td>
			&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<strong>Kamer: </strong>&nbsp;
		</td>
		<td>
			{$patient->getKamer()}
		</td>
	</tr>
	<tr>
		<td>
			<strong>Bed: </strong>&nbsp;
		</td>
		<td>
			{$patient->getBed()}
		</td>
	</tr>

</table>
</p>
<p>
	<div id="keukenpakket_form">
		{include file="keukenpakket_form.tpl"}
	</div>
</p>