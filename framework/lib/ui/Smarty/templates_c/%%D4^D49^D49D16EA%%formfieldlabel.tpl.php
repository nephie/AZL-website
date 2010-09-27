<?php /* Smarty version 2.6.18, created on 2010-04-28 11:21:23
         compiled from formfieldlabel.tpl */ ?>
<?php ob_start(); ?><?php echo get_class($this->_tpl_vars['field']) . '.tpl'; ?><?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('type', ob_get_contents());ob_end_clean(); ?>
<label for="<?php echo $this->_tpl_vars['field']->getId(); ?>
"  id="<?php echo $this->_tpl_vars['field']->getId(); ?>
_label"><?php echo $this->_tpl_vars['field']->getLabel(); ?>
<?php if ($this->_tpl_vars['type'] != "checkboxField.tpl"): ?>:<?php endif; ?> </label>