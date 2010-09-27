<?php /* Smarty version 2.6.18, created on 2010-04-29 09:52:55
         compiled from mycafetaria_hotmealperday.tpl */ ?>
<h1>Warme maaltijden van vandaag</h1>
<div class="headerline">&nbsp;</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygrid.tpl", 'smarty_include_vars' => array('grid' => $this->_tpl_vars['grid'],'columns' => "array('Besteld voor' => 'user', 'Afhaling' => array('column' => 'uur', 'modifier' => 'date_format:\"%H:%M\"' , 'width' => '120px'), 'Maaltijd' => 'meal')")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>