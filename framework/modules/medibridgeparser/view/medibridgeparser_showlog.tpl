<h1>Log voor bericht: {$log->getFilename()}</h1>
<div class="headerline">&nbsp;</div>
<div style="position: absolute; right: 0px; top: 0px;"><a href="#" onClick="{ajaxrequest request=$closerequest}">Sluiten</a></div>

<table>
	<tr>
		<td>
			<strong>Status:</strong>
		</td>
		<td>
			{$log->getStatusdelivery()}{if $log->getMessagedelivery() != ''}: {$log->getMessagedelivery()}{/if}
		</td>
	</tr>
	<tr>
		<td colspan="2">
			&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<strong>Verzender:</strong>
		</td>
		<td>
			{$log->getSender()} {if $plog->getSender() != $log->getSender()}({$plog->getSender()}){/if}
		</td>
	</tr>
	<tr>
		<td>
			<strong>Ontvanger:</strong>
		</td>
		<td>
			{$log->getReciever()} {if $plog->getReciever() != $log->getReciever()}({$plog->getReciever()}){/if}
		</td>
	</tr>
	<tr>
		<td>
			<strong>Verwerkingsdatum:</strong>
		</td>
		<td>
			{$log->getParsedate()|date_format:"%H:%M:%S - %d/%m/%Y"}
		</td>
	</tr>
	<tr>
		<td colspan="2">
			&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<strong>Bronbestand: </strong>
		</td>
		<td>
			{$basesourcemap}\<strong>{$log->getRelativesourcepath()}</strong>
		</td>
	</tr>
	{if $log->getStatusdelivery() == "DELIVERY_SUCCESS" || $log->getStatusdelivery() == "DELIVERY_ERROR"}
	<tr>
		<td>
			<strong>Doelbestand: </strong>
		</td>
		<td>
			{$basedestinationmap}\<strong>{$log->getRelativedestinationpath()}\{$log->getFilename()}</strong>
		</td>
	</tr>
	{/if}
	{if $log->getStatusdelivery() == "DELIVERY_ERROR" || $log->getStatusdelivery() == "PARSER_ERROR" || $log->getStatusdelivery() == "NO_PARSER" || $log->getStatusdelivery() == "MESSAGE_IGNORED"}
	<tr>
		<td>
			<strong>Bericht verplaatst naar: </strong>
		</td>
		<td>
			{$errormap}\<strong>{$log->getRelativeerrorpath()}</strong>
		</td>
	</tr>
	{/if}
	<tr>
		<td colspan="2">
			&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<strong>Status backup:</strong>
		</td>
		<td>
			{$log->getStatusbackup()}{if $log->getMessagebackup() != ''}: {$log->getMessagebackup()}{/if}
		</td>
	</tr>
	{if $log->getStatusbackup() != "NO_BACKUP_REQUESTED"}
	<tr>
		<td>
			<strong>Backup: </strong>
		</td>
		<td>
			{$backupmap}\<strong>{$log->getRelativebackuppath()}</strong>
		</td>
	</tr>
	{/if}
	{if $log->getStatuserror() == "ERROR_MOVE_ERROR"}
	<tr>
		<td colspan="2">
			Het bericht kon niet naar de foutmap verplaatst worden! Foutmelding: {$log->getMessageerror()}
		</td>
	</tr>
	{/if}
</table>
<p>
	<input id="editlink" type="button" onClick="{ajaxrequest request=$editrequest}" value="Bewerk dit bericht" />
</p>
<p>&nbsp;</p>