<?php /* Smarty version 2.6.18, created on 2010-04-28 11:21:54
         compiled from inlinetextField.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxform', 'inlinetextField.tpl', 11, false),)), $this); ?>
<?php if ($this->_tpl_vars['field']->getValue() != ''): ?>
	<?php $this->assign('fieldValue', $this->_tpl_vars['field']->getValue()); ?>
	<?php $this->assign('clear', false); ?>
<?php elseif ($this->_tpl_vars['field']->getDefaultvalue() != ''): ?>
	<?php $this->assign('fieldValue', $this->_tpl_vars['field']->getDefaultvalue()); ?>
	<?php $this->assign('clear', false); ?>
<?php else: ?>
	<?php $this->assign('fieldValue', $this->_tpl_vars['field']->getLabel()); ?>
	<?php $this->assign('clear', true); ?>
<?php endif; ?>
<input type="text" title="<?php echo $this->_tpl_vars['field']->getExtra(); ?>
" name="<?php echo $this->_tpl_vars['field']->getName(); ?>
" id="<?php echo $this->_tpl_vars['field']->getId(); ?>
" value="<?php echo $this->_tpl_vars['fieldValue']; ?>
" onKeydown="<?php echo 'if(window.event.keyCode == 13){'; ?>
<?php echo smarty_function_ajaxform(array('form' => $this->_tpl_vars['form']), $this);?>
<?php echo '};'; ?>
"
<?php if ($this->_tpl_vars['clear']): ?>
	onFocus="if (this.value == '<?php echo $this->_tpl_vars['fieldValue']; ?>
') <?php echo '{'; ?>
 this.value = ''; <?php echo '}'; ?>
" onBlur="if (this.value == '') <?php echo '{'; ?>
 this.value = '<?php echo $this->_tpl_vars['fieldValue']; ?>
'; <?php echo '}'; ?>
"
<?php endif; ?>
/>