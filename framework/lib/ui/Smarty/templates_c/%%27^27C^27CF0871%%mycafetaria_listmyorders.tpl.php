<?php /* Smarty version 2.6.18, created on 2010-04-28 11:21:53
         compiled from mycafetaria_listmyorders.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'mycafetaria_listmyorders.tpl', 27, false),array('modifier', 'string_format', 'mycafetaria_listmyorders.tpl', 43, false),)), $this); ?>
<h1>Reeds besteld</h1>
<div class="headerline">&nbsp;</div>
<p>
	Selecteer een persoon van wie u de bestellingen wenst te zien.
</p>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form.tpl", 'smarty_include_vars' => array('form' => $this->_tpl_vars['form'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if (isset ( $this->_tpl_vars['orders'] )): ?>
	<h1>De bestellingen voor vandaag van <?php echo $this->_tpl_vars['name']; ?>
</h1>
	<div class="headerline">&nbsp;</div>

	<?php if (count ( $this->_tpl_vars['orders'] ) == 0): ?>
		Er is nog niets besteld voor vandaag.
	<?php else: ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygrid.tpl", 'smarty_include_vars' => array('grid' => $this->_tpl_vars['myorderlisttoday'],'columns' => "array('Besteld op' => array('column' => 'orderuur', 'modifier' => 'date_format:\"%H:%M - %d/%m/%Y\"' , 'width' => '120px'),'Afhaling' => array('column' => 'uur', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"' , 'width' => '120px'),'Besteld door' => 'orderuser', 'Maaltijd' => 'meal',  'Prijs' => array('column' => 'price', 'modifier' => 'string_format:\"€%.2f\"' , 'width' => '40px'))")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<table>
		<?php $_from = $this->_tpl_vars['orders']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['order']):
?>
			<tr>
				<td>
					&nbsp;
				</td>
			</tr><tr>
				<td>
					<strong>Uur van afhaling: </strong>
				</td>
				<td>
					<?php echo ((is_array($_tmp=$this->_tpl_vars['order']->getUur())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%H:%M") : smarty_modifier_date_format($_tmp, "%H:%M")); ?>

				</td>
			</tr>
			<tr>
				<td>
					<strong>Maaltijd: </strong>
				</td>
				<td>
					<?php echo $this->_tpl_vars['order']->getMeal(); ?>

				</td>
			</tr>
			<tr>
				<td>
					<strong>Prijs: </strong>
				</td>
				<td>
					€<?php echo ((is_array($_tmp=$this->_tpl_vars['order']->getPrice())) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>

				</td>
			</tr><tr>
				<td>
					&nbsp;
				</td>
			</tr>
		<?php endforeach; endif; unset($_from); ?>
		</table>
	<?php endif; ?>
<?php endif; ?>
<?php if (isset ( $this->_tpl_vars['myorderlist'] )): ?>
	<p>
		&nbsp;
	</p>
	<h1>Al de bestellingen van <?php echo $this->_tpl_vars['name']; ?>
</h1>
	<div class="headerline">&nbsp;</div>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygrid.tpl", 'smarty_include_vars' => array('grid' => $this->_tpl_vars['myorderlist'],'columns' => "array('Besteld op' => array('column' => 'orderuur', 'modifier' => 'date_format:\"%H:%M - %d/%m/%Y\"' , 'width' => '120px'),'Afhaling' => array('column' => 'uur', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"' , 'width' => '120px'),'Besteld door' => 'orderuser', 'Maaltijd' => 'meal',  'Prijs' => array('column' => 'price', 'modifier' => 'string_format:\"€%.2f\"' , 'width' => '40px'))")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>