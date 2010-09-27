<div class="usertype">{include file="myvoorinschrijving_usertypeform.tpl" form=$form}</div>
{if $lijst instanceof pagerequest}<div class="lijstlink"><a href="{pagerequest request=$lijst}">lijst</a></div>{/if}
<h2>Voorinschrijving</h2>
Om de 10 minuten starten rondleidingen in groepen van ongeveer 20 personen, <strong>waarvoor inschrijving op voorhand verplicht is.</strong> Een volledig traject duurt ongeveer 1:30u<br />
<br />
<strong>Algemeen traject</strong><br />
Iedereen bezoekt laboratorium en medische beeldvorming (radiologie) ter plaatse.<br />
Iedereen kan in een tent op de parking de 100 - ziekenwagen en uitrusting voor dringende medische hulpverlening bezichtigen.<br />
<br />
Daarna kan u kiezen uit 3 deeltrajecten. Om u in te schrijven voor een bepaald traject dient u op het gewenste uur te klikken. Het op dit moment aantal vrije plaatsen voor een bepaald uur staat ernaast.<br /><br />
<br />
{foreach from=$mogelijkheden item=traject}
	<p>
		<strong>{$traject.traject->getName()}</strong><br />
		{$traject.traject->getDescription()}<br /><br />
		<span class="uren">
		{foreach from=$traject.uren item=uur name=uren}
			<a href="#" onClick="{ajaxrequest request=$uur.request}">{$uur.uur->getUur()}</a> ({$uur.vrij}){if !$smarty.foreach.uren.last}, {/if}
		{/foreach}
		</span>
	</p>
	<br />
{/foreach}