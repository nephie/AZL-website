<?php /* Smarty version 2.6.18, created on 2010-04-29 11:35:08
         compiled from mycafetaria_listallorders.tpl */ ?>
<h1>Al de bestellingen</h1>
<div class="headerline">&nbsp;</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygrid.tpl", 'smarty_include_vars' => array('grid' => $this->_tpl_vars['orderlist'],'columns' => "array('Afhaling' => array('column' => 'uur', 'modifier' => 'date_format:\"%H:%M - %d/%m/%Y\"' , 'width' => '120px'),'Besteld op' => array('column' => 'orderuur', 'modifier' => 'date_format:\"%H:%M - %d/%m/%Y\"' , 'width' => '120px'),'Besteld door' => 'orderuser', 'Besteld voor' => 'user', 'Maaltijd' => 'meal',  'Prijs' => array('column' => 'price', 'modifier' => 'string_format:\"€%.2f\"' , 'width' => '60px'), 'Geprint' => 'printed')")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>