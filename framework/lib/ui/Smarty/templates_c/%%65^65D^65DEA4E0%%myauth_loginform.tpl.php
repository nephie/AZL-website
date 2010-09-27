<?php /* Smarty version 2.6.18, created on 2010-04-28 22:05:49
         compiled from myauth_loginform.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxform', 'myauth_loginform.tpl', 26, false),)), $this); ?>
<h1>Inloggen</h1>
<div class="headerline">&nbsp;</div>
<p>
Geef hier uw persoonlijke gebruikersnaam en wachtwoord in om toegang te krijgen tot het beveiligde gedeelte van de site.
<br />
Indien u niet over deze gegevens beschikt, kan u de informatica-dienst van het AZ Lokeren hierover contacteren.
</p>
<p>
<?php if ($this->_tpl_vars['form'] instanceof form): ?>
<form onsubmit="return false;" id="<?php echo $this->_tpl_vars['form']->getId(); ?>
">
	<div class="formfields">
		<table>
		<?php $_from = $this->_tpl_vars['form']->getField(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
		<?php if (! $this->_tpl_vars['field'] instanceof hiddenField): ?>
			<tr>
				<td class="formlabel"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "formfieldlabel.tpl", 'smarty_include_vars' => array('field' => $this->_tpl_vars['field'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
				<td class="formfield"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "formField.tpl", 'smarty_include_vars' => array('field' => $this->_tpl_vars['field'])));
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
				<div class="formbuttons"><input type="submit" value="<?php echo $this->_tpl_vars['form']->getSubmittext(); ?>
" onclick="<?php echo smarty_function_ajaxform(array('form' => $this->_tpl_vars['form']), $this);?>
" /> </div>
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
</p>