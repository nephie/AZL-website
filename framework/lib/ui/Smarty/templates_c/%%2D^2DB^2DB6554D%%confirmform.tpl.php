<?php /* Smarty version 2.6.18, created on 2010-04-28 11:21:37
         compiled from confirmform.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxform', 'confirmform.tpl', 23, false),)), $this); ?>
<?php if ($this->_tpl_vars['form'] instanceof form): ?>
<?php if ($this->_tpl_vars['title'] <> ''): ?>
<h1><?php echo $this->_tpl_vars['title']; ?>
</h1>
<div class="headerline">&nbsp;</div>
<?php endif; ?>
<form onsubmit="return false;" id="<?php echo $this->_tpl_vars['form']->getId(); ?>
">
	<div class="formfields">
		<table>
		<?php $_from = $this->_tpl_vars['form']->getField(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
		<?php if (! $this->_tpl_vars['field'] instanceof hiddenField): ?>
			<tr>
				<td class="formlabel"><strong><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "formfieldlabel.tpl", 'smarty_include_vars' => array('field' => $this->_tpl_vars['field'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></strong></td>
				<td class="formfield"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "confirmformField.tpl", 'smarty_include_vars' => array('field' => $this->_tpl_vars['field'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
			</tr>
		<?php else: ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "formField.tpl", 'smarty_include_vars' => array('field' => $this->_tpl_vars['field'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		<tr>
			<td>&nbsp;</td>
			<td>
				<?php if (! $this->_tpl_vars['form']->isPhased() || $this->_tpl_vars['form']->isReady()): ?>
				<div class="formbuttons"><input type="submit" value="<?php echo $this->_tpl_vars['form']->getSubmittext(); ?>
" onclick="tinyMCE.triggerSave(false, false);<?php echo smarty_function_ajaxform(array('form' => $this->_tpl_vars['form']), $this);?>
" /> <input type="reset" value="Wijzigen" onclick="tinyMCE.triggerSave(false, false);<?php echo smarty_function_ajaxform(array('abort' => 'true','form' => $this->_tpl_vars['form']), $this);?>
" /></div>
				<?php elseif ($this->_tpl_vars['form']->isPhased() && ! $this->_tpl_vars['form']->isReady()): ?>
				<div class="formbuttons"><input type="submit" value="<?php echo $this->_tpl_vars['form']->getSubmittext(); ?>
" onclick="" /><input type="reset" value="Wijzigen" onclick="tinyMCE.triggerSave(false, false);<?php echo smarty_function_ajaxform(array('abort' => 'true','form' => $this->_tpl_vars['form']), $this);?>
" /></div>
				<?php endif; ?>

				<input type="hidden" id="hidden_form_id" name="hidden_form_id" value="<?php echo $this->_tpl_vars['form']->getId(); ?>
" />
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<div class="formerror" id="formerror_<?php echo $this->_tpl_vars['form']->getId(); ?>
">&nbsp;</div>
			</td>
		</tr>
		</table>
		</div>
</form>
<?php endif; ?>