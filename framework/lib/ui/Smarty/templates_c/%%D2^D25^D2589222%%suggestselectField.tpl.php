<?php /* Smarty version 2.6.18, created on 2010-04-30 11:55:26
         compiled from suggestselectField.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'extraparamlist', 'suggestselectField.tpl', 7, false),)), $this); ?>
<?php if ($this->_tpl_vars['field']->getValue2() != ''): ?>
	<?php $this->assign('fieldValue2', $this->_tpl_vars['field']->getValue2()); ?>
<?php else: ?>
	<?php $this->assign('fieldValue2', $this->_tpl_vars['field']->getDefaultvalue()); ?>
<?php endif; ?>

<input autocomplete="off" type="text" title="<?php echo $this->_tpl_vars['field']->getExtra(); ?>
" name="<?php echo $this->_tpl_vars['field']->getName(); ?>
_text[]" id="<?php echo $this->_tpl_vars['field']->getId(); ?>
_text" value="<?php echo $this->_tpl_vars['fieldValue2']; ?>
" onFocus="suggestselect_handlefocusin(this, '<?php echo $this->_tpl_vars['field']->getCallbackcontroller(); ?>
', '<?php echo $this->_tpl_vars['field']->getCallbackfunction(); ?>
', '<?php echo smarty_function_extraparamlist(array('field' => $this->_tpl_vars['field']), $this);?>
');" onBlur="suggest_handlefocusout(this);" onKeyPress="return nosubmitonenter(event,this)" onKeyUp="suggestselect_handlekeyup(event, this, '<?php echo $this->_tpl_vars['field']->getCallbackcontroller(); ?>
', '<?php echo $this->_tpl_vars['field']->getCallbackfunction(); ?>
', '<?php echo smarty_function_extraparamlist(array('field' => $this->_tpl_vars['field']), $this);?>
');" />
<br/>
<br />

<?php if ($this->_tpl_vars['field']->getMultiple()): ?>
	<?php $this->assign('multiple', 'multiple="multiple"'); ?>
	<?php $this->assign('class', 'class="multipleSelect"'); ?>
<?php else: ?>
	<?php $this->assign('multiple', ""); ?>
	<?php $this->assign('class', ""); ?>
<?php endif; ?>
<div id="<?php echo $this->_tpl_vars['field']->getId(); ?>
_container">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "selectField.tpl", 'smarty_include_vars' => array('field' => $this->_tpl_vars['field'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>