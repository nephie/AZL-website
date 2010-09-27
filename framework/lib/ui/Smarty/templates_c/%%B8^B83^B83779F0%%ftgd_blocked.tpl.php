<?php /* Smarty version 2.6.18, created on 2010-05-03 14:08:24
         compiled from ftgd_blocked.tpl */ ?>
<h1>Fortiguard logs: Blocked</h1>
<div class="headerline">&nbsp;</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygrid.tpl", 'smarty_include_vars' => array('grid' => $this->_tpl_vars['blocked'],'columns' => "array('time' => array('column' => 'time', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M:%S\"' , 'width' => '106px'),'Gebruiker' => 'user','Groep' => 'group','PC ip' => 'sourceip','Host' => 'host','Categorie' => 'cat','URL' => 'url', 'Surf' => 'goto')")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>