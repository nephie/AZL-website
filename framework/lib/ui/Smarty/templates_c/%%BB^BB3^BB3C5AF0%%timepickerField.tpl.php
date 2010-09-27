<?php /* Smarty version 2.6.18, created on 2010-08-31 13:55:49
         compiled from timepickerField.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxform', 'timepickerField.tpl', 7, false),)), $this); ?>
<?php if ($this->_tpl_vars['field']->getValue() != ''): ?>
	<?php $this->assign('fieldValue', $this->_tpl_vars['field']->getValue()); ?>
<?php else: ?>
	<?php $this->assign('fieldValue', $this->_tpl_vars['field']->getDefaultvalue()); ?>
<?php endif; ?>
	<?php $this->assign('class', 'timepicker'); ?>
<input type="text" title="<?php echo $this->_tpl_vars['field']->getExtra(); ?>
" class="<?php echo $this->_tpl_vars['class']; ?>
" name="<?php echo $this->_tpl_vars['field']->getName(); ?>
" id="<?php echo $this->_tpl_vars['field']->getId(); ?>
" value="<?php echo $this->_tpl_vars['fieldValue']; ?>
" onEnter="<?php echo smarty_function_ajaxform(array('form' => $this->_tpl_vars['form']), $this);?>
" />
<?php echo $this->_tpl_vars['field']->loaddatepicker(); ?>