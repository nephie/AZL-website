<?php /* Smarty version 2.6.18, created on 2010-04-28 13:44:22
         compiled from confirmcheckboxField.tpl */ ?>
<?php if ($this->_tpl_vars['field']->isSelected()): ?>
		Ja
<?php else: ?>
		Nee
<?php endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "hiddenField.tpl", 'smarty_include_vars' => array('field' => $this->_tpl_vars['field'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>