<?php /* Smarty version 2.6.18, created on 2010-04-28 13:43:22
         compiled from checkboxField.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxform', 'checkboxField.tpl', 6, false),)), $this); ?>
<?php if ($this->_tpl_vars['field']->isSelected()): ?>
		<?php $this->assign('checked', 'checked="checked"'); ?>
<?php else: ?>
		<?php $this->assign('checked', ''); ?>
<?php endif; ?>
<input class="checkbox" title="<?php echo $this->_tpl_vars['field']->getExtra(); ?>
" type="checkbox" name="<?php echo $this->_tpl_vars['field']->getName(); ?>
" id="<?php echo $this->_tpl_vars['field']->getId(); ?>
"  value="<?php echo $this->_tpl_vars['field']->getDefaultvalue(); ?>
" <?php echo $this->_tpl_vars['checked']; ?>
 onEnter="<?php echo smarty_function_ajaxform(array('form' => $this->_tpl_vars['form']), $this);?>
" <?php if ($this->_tpl_vars['form']->isPhased()): ?> onchange="<?php echo smarty_function_ajaxform(array('form' => $this->_tpl_vars['form'],'notfinal' => true,'field' => $this->_tpl_vars['field']->getName()), $this);?>
" onclick="this.blur();" <?php endif; ?>/>