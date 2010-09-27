<?php /* Smarty version 2.6.18, created on 2010-04-29 08:34:02
         compiled from mycafetaria_statsperday.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxrequest', 'mycafetaria_statsperday.tpl', 11, false),)), $this); ?>
<h1>Statistieken</h1>
<div class="headerline">&nbsp;</div>
<p>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form.tpl", 'smarty_include_vars' => array('form' => $this->_tpl_vars['form'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</p>
<p>
<table>
<?php $_from = $this->_tpl_vars['count']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['name'] => $this->_tpl_vars['mealcount']):
?>
	<tr>
		<td>
			<strong><a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['mealcount']['request']), $this);?>
"><?php echo $this->_tpl_vars['name']; ?>
</a> : </strong>
		</td>
		<td>
			<?php echo $this->_tpl_vars['mealcount']['count']; ?>

		</td>
	</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
</p>
<div id="grid_mealsperday">
</div>