<div style="position: relative">
<h1>Dokter van wacht: {$specialisme->getName()}</h1>
<div style="position: absolute; right: 0px; top: 0px;"><a href="#" onClick="{ajaxrequest request=$closerequest}">Terug naar overzicht</a></div>
<div class="headerline">&nbsp;</div>
</div>
<p>
	<div id="specgrid_{$specialisme->getId()}"></div>
	<table class="grid">
		<tr class="gridhead">
			<td style="vertical-align: middle; width: 10px; text-align: left; position: relative;" >
					
			</td>
			<td>
				Datum
			</td>
			
			<td>
				<table>
				
					<tr>
						<td style="width: 50px;">
							&nbsp;
						</td>
						<td style="width: 75px;">
							Van
						</td>
						<td style="width: 75px;">
							Tot
						</td>
						<td>
							Dokter
						</td>
					</tr>
				
				</table>
			</td>
		</tr>
		{foreach from=$list item=day}
		{cycle values="gridrow_A,gridrow_B" assign=rowcycle}
		<tr class="gridrow {$rowcycle}">
			<td>
				
			</td>
			<td>
				{if $day.addrequest instanceof ajaxrequest}
						<a onclick="{ajaxrequest request=$day.addrequest}" href="#">
							{$day.start|date_format:"%d/%m/%Y"}					
						</a>
				{else}
					{$day.start|date_format:"%d/%m/%Y"}
				{/if}
				
			</td>
			<td>
				<table>
				{foreach from=$day.dokters item=dokter}
					<tr>
						<td style="width: 50px;">
							{if $dokter.request instanceof ajaxrequest}
								<a onclick="{ajaxrequest request=$dokter.request}" href="#">
								<span><img id="delbutton" title="Verwijderen" src="files/images/delete.png"></span>
							{/if}
						</td>
						<td style="width: 75px;">
							{if $dokter.dokter->getStart() < $day.start}
								00:00
							{else}							
								{$dokter.dokter->getStart()|date_format:"%H:%M"}
							{/if}
						</td>
						<td style="width: 75px;">
							{if $dokter.dokter->getStop() > $day.start + 86400 -1}
								23:59
							{else}
								{$dokter.dokter->getStop()|date_format:"%H:%M"}
							{/if}
						</td>
						<td>
							Dr. {$dokter.dokter->getNaam()} {$dokter.dokter->getVoornaam()}
						</td>
					</tr>
				{/foreach}
				</table>
			</td>
		</tr>
		{/foreach}
		<tr class="gridfoot">
			<td colspan="999">
				<span><a href="#" onClick="{ajaxrequest request=$prevrequest}">Vorige maand</a></span>
			
				<span style="float: right;"><a href="#" onClick="{ajaxrequest request=$nextrequest}">Volgende maand</a></span>
			</td>
		</tr>
	</table>
</p>
<p>
<div id="acllist_wachtdokter_{$specialisme->getId()}"></div>	
</p>