<?php /* Smarty version 2.6.18, created on 2010-04-28 22:05:50
         compiled from passwordField.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxform', 'passwordField.tpl', 1, false),)), $this); ?>
<input type="password" title="<?php echo $this->_tpl_vars['field']->getExtra(); ?>
" name="<?php echo $this->_tpl_vars['field']->getName(); ?>
"  id="<?php echo $this->_tpl_vars['field']->getId(); ?>
" onEnter="<?php echo smarty_function_ajaxform(array('form' => $this->_tpl_vars['form']), $this);?>
" />