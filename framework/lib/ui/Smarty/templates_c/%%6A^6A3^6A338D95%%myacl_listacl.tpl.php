<?php /* Smarty version 2.6.18, created on 2010-04-29 09:54:08
         compiled from myacl_listacl.tpl */ ?>
<?php if ($this->_tpl_vars['showrequester']): ?>
	<?php $this->assign('requester', "'Aanvrager' => 'requester',"); ?>
<?php else: ?>
	<?php $this->assign('requester', ""); ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['showobject']): ?>
	<?php $this->assign('object', "'Item' => 'object',"); ?>
<?php else: ?>
	<?php $this->assign('object', ""); ?>
<?php endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygrid.tpl", 'smarty_include_vars' => array('grid' => $this->_tpl_vars['acllist'],'columns' => "array(".($this->_tpl_vars['requester']).($this->_tpl_vars['object'])."'Recht' => 'rightdesc')")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>