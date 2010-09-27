{if $terug instanceof pagerequest}<a href="{pagerequest request=$terug}">Terug</a><br />{/if}
{if $lijst instanceof pagerequest}<div class="lijstlink"><a href="{pagerequest request=$lijst}">lijst</a></div>{/if}
<h2>Voorinschrijving</h2>
U bent ingeschreven met de volgende gegevens:<br />
<ul>
<li>Traject: {$traject->getName()}
<li>Uur: {$uur->getUur()}
<li>Voornaam: {$gast->getVoornaam()}
<li>Achternaam: {$gast->getAchternaam()}
<li>Woonplaats: {$gast->getWoonplaats()}
<li>E-Mail: {$gast->getMailaddress()}
<li>Aantal: {$gast->getAantal()}
</ul>
<br /><br />
Gelieve deze gegevens bij de hand te hebben als u zich aanmeld.
<br />
<br />
Om het normale verkeer naar het ziekenhuis (ziekenwagens, artsen, personeel en bezoekers) in goede 
banen te leiden hebben we voor die dag de <strong>parking IDM, Zelebaan 42, Lokeren</strong> 
exclusief gereserveerd voor bezoekers aan de OBD.  Een <strong>pendelbusverbinding</strong> naar het ziekenhuis is voorzien.  
Voorzie best een korte tijdsmarge tussen aankomst op de parking en de start van het gekozen traject.