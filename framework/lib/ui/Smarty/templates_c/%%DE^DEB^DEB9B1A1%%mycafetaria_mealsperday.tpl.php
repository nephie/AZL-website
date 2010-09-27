<?php /* Smarty version 2.6.18, created on 2010-04-29 09:35:30
         compiled from mycafetaria_mealsperday.tpl */ ?>
<?php if (isset ( $this->_tpl_vars['grid'] )): ?>
	<p>
		&nbsp;
	</p>
	<h1><?php echo $this->_tpl_vars['meal']; ?>
</h1>
	<div class="headerline">&nbsp;</div>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygrid.tpl", 'smarty_include_vars' => array('grid' => $this->_tpl_vars['grid'],'columns' => "array('Afhaling' => array('column' => 'uur', 'modifier' => 'date_format:\"%H:%M - %d/%m/%Y\"' , 'width' => '120px'),'Besteld op' => array('column' => 'orderuur', 'modifier' => 'date_format:\"%H:%M - %d/%m/%Y\"' , 'width' => '120px'),'Besteld door' => 'orderuser', 'Besteld voor' => 'user', 'Maaltijd' => 'meal',  'Prijs' => array('column' => 'price', 'modifier' => 'string_format:\"€%.2f\"' , 'width' => '60px'), 'Geprint' => 'printed')")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>