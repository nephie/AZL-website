<p>
Bent u zeker dat u dit wilt verwijderen?
</p>
<p>
	<table>
		<tr>
			<td>			
				<strong>Start: </strong>
			</td>
			<td>
				{$dokter->getStart()|date_format:"%d/%m/%Y - %H:%M:%S"}
			</td>
		</tr>
		<tr>
			<td>			
				<strong>Stop: </strong>
			</td>
			<td>
				{$dokter->getStop()|date_format:"%d/%m/%Y - %H:%M:%S"}
			</td>
		</tr>
		<tr>
			<td>			
				<strong>Dokter: </strong>
			</td>
			<td>
				Dr. {$dokter->getNaam()} {$dokter->getVoornaam()}
			</td>
		</tr>
	</table>
</p>
<p class="formbuttons">
	<input type="button" value="Verwijderen" onClick="{ajaxrequest request=$yes}"/> &nbsp;&nbsp;&nbsp;
	<input type="button" value="Terug" onClick="{ajaxrequest request=$no}"/>
</p>
<br />
<br />