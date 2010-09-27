<?php /* Smarty version 2.6.18, created on 2010-05-05 12:45:23
         compiled from mycafetaria_manageoptions.tpl */ ?>
<h1>Beheer opties</h1>
<div class="headerline">&nbsp;</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygrid.tpl", 'smarty_include_vars' => array('grid' => $this->_tpl_vars['options'],'columns' => "array('Optie' => 'name', 'Prijs' => array('column' => 'price', 'modifier' => 'string_format:\"€%.2f\"'),'Prijs externen' => array('column' => 'price2', 'modifier' => 'string_format:\"€%.2f\"'))")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>