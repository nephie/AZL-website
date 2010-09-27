<div class="usertype">{include file="myvoorinschrijving_usertypeform.tpl" form=$form}</div>
{if $terug instanceof pagerequest}<div class="lijstlink"><a href="{pagerequest request=$terug}">Terug</a></div>{/if}
{foreach from=$lijst item=dag}
	<h1>{$dag.dag}</h1>
	{foreach from=$dag.trajecten item=traject}
		<h2>{$traject.traject->getName()}</h2>
		{foreach from=$traject.uren item=uur}
			<h3>{$uur.uur->getUur()} ({$uur.aantal} ingeschreven)</h3>
			<table class="lijst" cellspacing="0" cellpadding="0" style="width: 100%;">
			{foreach from=$uur.gasten item=gast}
				{cycle values="#FFFFFF,#E6E6E6" assign=bgcolor name=bgcolor}
				<tr id="{$gast.gast->getId()}_rij" style="background-color: {$bgcolor};">
					<td>
						{if $gast.new == "true"}<span style="color:red;">*</span>{/if}
					</td>
					<td valign="top">
						{$gast.gast->getVoornaam()} {$gast.gast->getAchternaam()}
					</td>
					<td valign="top">
						{$gast.gast->getMailaddress()}
					</td>
					<td valign="top">
						{$gast.gast->getAantal()} personen
					</td>
					<td id="{$gast.gast->getId()}_morelink">
						{include file="myvoorinschrijving_changelink.tpl" request=$gast.showmorerequest linktext="Meer informatie"}
					</td>
					<td>
						<a href="javascript:void(0)" onclick="{ajaxrequest request=$gast.deleterequest}">Verwijder</a>
					</td>
				</tr>
				<tr style="background-color: {$bgcolor};">
					<td colspan="6" style="width: 100%;">
						<div style="display:none;" id="{$gast.gast->getId()}_more">
						<ul >
							<li>{$gast.gast->getRegistrationtime()|date_format:"%Y-%m-%d %H:%M:%S"}</li>
							<li>{$gast.user->getDescription()}</li>
							<li>{if $gast.sameip <> 0}
										{$gast.sameip.aantal} gasten van hetzelfde IP-adres <span id="{$gast.gast->getId()}_samelink">{include file="myvoorinschrijving_changelink.tpl" request=$gast.sameip.showrequest linktext="Toon"}</span> ({$gast.gast->getIpaddress()})
									{else}
										Uniek IP-adres
									{/if}</li>
						</ul>
						<table cellspacing="0" cellpadding="0" style="width: 100%;">							
							<tr id="{$gast.gast->getId()}_same" style="display:none; background-color: {$bgcolor};">
								<td valign="top" style="width: 100%;">
									{if $gast.sameip <> 0}										
										<table class="lijst" cellspacing="0" cellpadding="0" style="width: 100%;">
										{foreach from=$gast.sameip.wie item=samegast}
										{cycle name=bgcolor2 values="#FFFFFF,#E6E6E6" assign=bgcolor2}
											<tr style="background-color: {$bgcolor2};">
												<td valign="top">
													{$samegast->getVoornaam()} {$samegast->getAchternaam()}
												</td>
												<td valign="top">
													{$samegast->getMailaddress()}
												</td>
												<td valign="top">
													{$samegast->getAantal()} personen
												</td>
												<td valign="top">
													{$samegast->getRegistrationtime()|date_format:"%Y-%m-%d %H:%M:%S"}
												</td>
											</tr>
										{/foreach}
										</table>
								{/if}
								</td>
							</tr>
						</table>
					</div>
					</td>
				</tr>	
						
				
			{/foreach}
			</table>
		{/foreach}
	{/foreach}
{/foreach}