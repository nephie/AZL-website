<?php /* Smarty version 2.6.18, created on 2010-04-29 09:54:34
         compiled from rteField.tpl */ ?>
<?php if ($this->_tpl_vars['field']->getValue() != ''): ?>
	<?php $this->assign('fieldValue', $this->_tpl_vars['field']->getValue()); ?>
<?php else: ?>
	<?php $this->assign('fieldValue', $this->_tpl_vars['field']->getDefaultvalue()); ?>
<?php endif; ?>
<textarea name="<?php echo $this->_tpl_vars['field']->getName(); ?>
" title="<?php echo $this->_tpl_vars['field']->getExtra(); ?>
" id="<?php echo $this->_tpl_vars['field']->getId(); ?>
"><?php echo $this->_tpl_vars['fieldValue']; ?>
</textarea>
<?php echo $this->_tpl_vars['field']->loadrte(); ?>