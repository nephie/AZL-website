<div id="logcontainer" class="extracontainer" style="position: relative;"></div>
<h1>Fouten die aandacht vereisen</h1>
<div class="headerline">&nbsp;</div>
{include file="mygrid.tpl" grid=$errorlist columns="array('Filename' => 'filename', 'Verstuurder' => 'sender', 'Ontvanger' => 'reciever', 'Verwerkingsdatum' => array('column' => 'parsedate', 'modifier' => 'date_format:\"%H:%M:%S - %d/%m/%Y\"'), 'Status' => 'statusdelivery')"}
<br /><br /><br />
<h1>Alle Logs</h1>
<div class="headerline">&nbsp;</div>
{include file="mygrid.tpl" grid=$loglist columns="array('Filename' => 'filename', 'Verstuurder' => 'sender', 'Ontvanger' => 'reciever', 'Verwerkingsdatum' => array('column' => 'parsedate', 'modifier' => 'date_format:\"%H:%M:%S - %d/%m/%Y\"'), 'Status' => 'statusdelivery')"}