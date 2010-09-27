<h1>Keukenpakket</h1>
<div class="headerline">&nbsp;</div>
{include file="form.tpl" form=$form}
<strong>Aantal patiënten: {$count}</strong>
{include file="mygrid.tpl" grid=$grid columns="array('Kamer' => 'kamer', 'Bed' => 'bed', 'Voornaam' => 'voornaam', 'Achternaam' => 'achternaam')"}