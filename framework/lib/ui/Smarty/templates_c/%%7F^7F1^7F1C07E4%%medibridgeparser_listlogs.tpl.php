<?php /* Smarty version 2.6.18, created on 2010-04-29 09:05:14
         compiled from medibridgeparser_listlogs.tpl */ ?>
<div id="logcontainer" class="extracontainer" style="position: relative;"></div>
<h1>Fouten die aandacht vereisen</h1>
<div class="headerline">&nbsp;</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygrid.tpl", 'smarty_include_vars' => array('grid' => $this->_tpl_vars['errorlist'],'columns' => "array('Filename' => 'filename', 'Verstuurder' => 'sender', 'Ontvanger' => 'reciever', 'Verwerkingsdatum' => array('column' => 'parsedate', 'modifier' => 'date_format:\"%H:%M:%S - %d/%m/%Y\"'), 'Status' => 'statusdelivery')")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br /><br /><br />
<h1>Alle Logs</h1>
<div class="headerline">&nbsp;</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygrid.tpl", 'smarty_include_vars' => array('grid' => $this->_tpl_vars['loglist'],'columns' => "array('Filename' => 'filename', 'Verstuurder' => 'sender', 'Ontvanger' => 'reciever', 'Verwerkingsdatum' => array('column' => 'parsedate', 'modifier' => 'date_format:\"%H:%M:%S - %d/%m/%Y\"'), 'Status' => 'statusdelivery')")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>