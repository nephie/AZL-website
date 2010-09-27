{if $lijst instanceof pagerequest}<div class="lijstlink"><a href="{pagerequest request=$lijst}">lijst</a></div>{/if}
<h2>Voorinschrijving</h2>
<p>
	Om uw inschrijving te kunnen vervolledigen hebben we enkele gegevens van u nodig. Gelieve het onderstaande formulier in te vullen.<br />
	Indien u zich vergist heeft en u zich voor een ander uur/traject wenst in te schrijven kan u <a href="{pagerequest request=$terug}">terug gaan</a>.
</p>
{include file="form.tpl" form=$form}