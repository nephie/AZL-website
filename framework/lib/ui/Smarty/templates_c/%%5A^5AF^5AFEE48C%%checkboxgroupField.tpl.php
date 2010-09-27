<?php /* Smarty version 2.6.18, created on 2010-04-28 11:21:31
         compiled from checkboxgroupField.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxform', 'checkboxgroupField.tpl', 7, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['field']->getBoxes(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['box']):
?>
<?php if ($this->_tpl_vars['box']['selected']): ?>
		<?php $this->assign('checked', 'checked="checked"'); ?>
<?php else: ?>
		<?php $this->assign('checked', ''); ?>
<?php endif; ?>
<input class="checkbox" title="<?php echo $this->_tpl_vars['field']->getExtra(); ?>
" type="checkbox" name="<?php echo $this->_tpl_vars['field']->getName(); ?>
[]"   value="<?php echo $this->_tpl_vars['id']; ?>
" <?php echo $this->_tpl_vars['checked']; ?>
 onEnter="<?php echo smarty_function_ajaxform(array('form' => $this->_tpl_vars['form']), $this);?>
" <?php if ($this->_tpl_vars['form']->isPhased()): ?> onChange="<?php echo smarty_function_ajaxform(array('form' => $this->_tpl_vars['form'],'notfinal' => true,'field' => $this->_tpl_vars['field']->getName()), $this);?>
" onclick="this.blur();"<?php endif; ?>/>&nbsp;<?php echo $this->_tpl_vars['box']['name']; ?>
&nbsp;&nbsp;&nbsp;&nbsp;
<?php endforeach; endif; unset($_from); ?>