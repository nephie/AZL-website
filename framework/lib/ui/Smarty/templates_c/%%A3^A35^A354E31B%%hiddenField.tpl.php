<?php /* Smarty version 2.6.18, created on 2010-04-28 11:21:37
         compiled from hiddenField.tpl */ ?>
<?php $this->assign('fieldValue', $this->_tpl_vars['field']->getValue()); ?>
<input type="hidden" name="<?php echo $this->_tpl_vars['field']->getName(); ?>
" id="<?php echo $this->_tpl_vars['field']->getId(); ?>
" value="<?php echo $this->_tpl_vars['fieldValue']; ?>
" />