<?php /* Smarty version 2.6.18, created on 2010-04-28 11:21:54
         compiled from inlineform.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxform', 'inlineform.tpl', 13, false),)), $this); ?>
<?php if ($this->_tpl_vars['form'] instanceof form): ?>
<span  class="inlineform">
<form onsubmit="return false;" id="<?php echo $this->_tpl_vars['form']->getId(); ?>
">
<span class="formfields">
<?php $_from = $this->_tpl_vars['form']->getField(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
	<?php if (! $this->_tpl_vars['field'] instanceof hiddenField): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "formField.tpl", 'smarty_include_vars' => array('field' => $this->_tpl_vars['field'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "formfieldvalidateerror.tpl", 'smarty_include_vars' => array('validateerror' => ($this->_tpl_vars['field'])."-getValidateerror()")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php else: ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "formField.tpl", 'smarty_include_vars' => array('field' => $this->_tpl_vars['field'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</span>
<span class="formbuttons"><input type="submit" value="<?php echo $this->_tpl_vars['form']->getSubmittext(); ?>
" onclick="<?php echo smarty_function_ajaxform(array('form' => $this->_tpl_vars['form']), $this);?>
" /></span>
<input type="hidden" id="hidden_form_id" name="hidden_form_id" value="<?php echo $this->_tpl_vars['form']->getId(); ?>
" />
</form>
</span>
<?php else: ?>
meh
<?php endif; ?>