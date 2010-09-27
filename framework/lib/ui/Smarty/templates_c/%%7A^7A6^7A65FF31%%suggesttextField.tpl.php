<?php /* Smarty version 2.6.18, created on 2010-04-28 11:21:23
         compiled from suggesttextField.tpl */ ?>
<?php if ($this->_tpl_vars['field']->getValue() != ''): ?>
	<?php $this->assign('fieldValue', $this->_tpl_vars['field']->getValue()); ?>
<?php else: ?>
	<?php $this->assign('fieldValue', $this->_tpl_vars['field']->getDefaultvalue()); ?>
<?php endif; ?>
<input autocomplete="off" type="text" title="<?php echo $this->_tpl_vars['field']->getExtra(); ?>
" name="<?php echo $this->_tpl_vars['field']->getName(); ?>
" id="<?php echo $this->_tpl_vars['field']->getId(); ?>
" value="<?php echo $this->_tpl_vars['fieldValue']; ?>
" onFocus="suggest_handlefocusin(this, '<?php echo $this->_tpl_vars['field']->getCallbackcontroller(); ?>
', '<?php echo $this->_tpl_vars['field']->getCallbackfunction(); ?>
');" onKeyPress="return nosubmitonenter(event,this)" onKeyUp="suggest_handlekeyup(event, this, '<?php echo $this->_tpl_vars['field']->getCallbackcontroller(); ?>
', '<?php echo $this->_tpl_vars['field']->getCallbackfunction(); ?>
')" />
<div style="display:none;" class="suggestresult" id="<?php echo $this->_tpl_vars['field']->getId(); ?>
_result"></div>