<h1>Melding [{$ticket->getId()}] aan [{$ticket->getToname()}]: {$ticket->getTitel()}</h1>
<div class="headerline">&nbsp;</div>
<div style="position: absolute; right: 0px; top: 0px;"><a href="#" onClick="{ajaxrequest request=$closerequest}">Sluiten</a></div>
<table>
	<tr>
		<td valign="top"><strong>Contact:</strong></td>
		<td>{$ticket->getContact()}</td>
	</tr>

	<tr>
		<td valign="top"><strong>Afdeling:</strong></td>
		<td>{$ticket->getDienst()}</td>
	</tr>

	<tr>
		<td valign="top"><strong>Gemeld op:</strong></td>
		<td>{$ticket->getTime()|date_format:"%d/%m/%Y - %H:%M"}</td>
	</tr>

	<tr>
		<td valign="top"><strong>Status:</strong></td>
		<td>{$ticket->getStatus()}</td>
	</tr>

	<tr>
		<td valign="top"><strong>Gemeld door:</strong></td>
		<td>{$ticket->getUser()}</td>
	</tr>

	<tr>
		<td valign="top"><strong>Melding:</strong></td>
		<td>{$ticket->getMessage()|nl2br}</td>
	</tr>
</table>
<br />
<br />