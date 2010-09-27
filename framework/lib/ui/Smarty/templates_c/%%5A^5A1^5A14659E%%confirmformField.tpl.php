<?php /* Smarty version 2.6.18, created on 2010-04-28 11:21:37
         compiled from confirmformField.tpl */ ?>
<?php if ($this->_tpl_vars['field'] instanceof formField): ?>
	<?php ob_start(); ?><?php echo 'confirm' . get_class($this->_tpl_vars['field']) . '.tpl'; ?><?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('template', ob_get_contents());ob_end_clean(); ?>

	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['template'], 'smarty_include_vars' => array('field' => $this->_tpl_vars['field'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php endif; ?>