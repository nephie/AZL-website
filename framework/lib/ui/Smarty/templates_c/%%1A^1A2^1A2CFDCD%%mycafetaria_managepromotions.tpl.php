<?php /* Smarty version 2.6.18, created on 2010-04-30 09:10:38
         compiled from mycafetaria_managepromotions.tpl */ ?>
<h1>Promoties beheren</h1>
<div class="headerline">&nbsp;</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygrid.tpl", 'smarty_include_vars' => array('grid' => $this->_tpl_vars['promotions'],'columns' => "array('Type' => 'name','Start' => array('column' => 'starttime', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"'),'Einde' => array('column' => 'stoptime', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"'))")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<h1>Promotie teksten</h1>
<div class="headerline">&nbsp;</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygrid.tpl", 'smarty_include_vars' => array('grid' => $this->_tpl_vars['articles'],'columns' => "array('Werktitel' => 'alias', 'Aangemaakt door' => 'articleauthorname', 'Aanmaakdatum' => array('column' => 'articlecreationdate','modifier' => 'date_format:\"%H:%M - %d/%m/%Y\"'))")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>