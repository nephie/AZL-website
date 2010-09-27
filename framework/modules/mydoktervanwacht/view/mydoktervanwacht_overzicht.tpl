<h1>Dokter van wacht</h1>
<div class="headerline">&nbsp;</div>
<p>
<table class="grid">
	<tr class="gridhead">
		<th>
			Specialisme
		</th>
		<th>
			Dokter
		</th>
		<th>
			Van
		</th>
		<th>
			Tot
		</th>
	</tr>
{foreach from=$list item="spec"}
{cycle values="gridrow_A,gridrow_B" assign=rowcycle}
	<tr class="gridrow {$rowcycle}">
		<td style="padding-left: 5px;">
			<strong><a href="#" onClick="{ajaxrequest request=$spec.request}">{$spec.name}</a></strong>
		</td>
		<td>
			{if $spec.wachtdokter instanceof wachtdokterviewObject}
				Dr. {$spec.wachtdokter->getNaam()} {$spec.wachtdokter->getVoornaam()}
			{/if}
		</td>
		<td>
			{if $spec.wachtdokter instanceof wachtdokterviewObject}
				{$spec.wachtdokter->getStart()|date_format:"%d/%m/%Y - %H:%M:%S"}
			{/if}
		</td>
		<td>
			{if $spec.wachtdokter instanceof wachtdokterviewObject}
				{$spec.wachtdokter->getStop()|date_format:"%d/%m/%Y - %H:%M:%S"}
			{/if}
		</td>
	</tr>
{/foreach}
	<tr class="gridfoot">
		<td>
&nbsp;
		</td>
		<td>

		</td>
		<td>
			
		</td>
		<td>
			
		</td>
	</tr>
</table>
</p>