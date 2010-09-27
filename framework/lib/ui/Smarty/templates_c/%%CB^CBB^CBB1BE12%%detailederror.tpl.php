<?php /* Smarty version 2.6.18, created on 2010-08-19 10:44:11
         compiled from detailederror.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'print_r', 'detailederror.tpl', 2, false),)), $this); ?>
<strong><?php echo $this->_tpl_vars['exception']->getMessage(); ?>
</strong> in <strong><?php echo $this->_tpl_vars['exception']->getFile(); ?>
</strong> on line <strong><?php echo $this->_tpl_vars['exception']->getLine(); ?>
</strong> <div id="show<?php echo $this->_tpl_vars['exceptionid']; ?>
">(<a href ="javascript:;" onclick="xajax.dom.assign('<?php echo $this->_tpl_vars['exceptionid']; ?>
' , 'style.display' , 'block');xajax.dom.assign('show<?php echo $this->_tpl_vars['exceptionid']; ?>
' , 'style.display' , 'none');">show trace</a>)</div>
<div id="<?php echo $this->_tpl_vars['exceptionid']; ?>
" style="display:none;">(<a href="javascript:;" onclick="xajax.dom.assign('<?php echo $this->_tpl_vars['exceptionid']; ?>
' , 'style.display' , 'none');xajax.dom.assign('show<?php echo $this->_tpl_vars['exceptionid']; ?>
' , 'style.display' , 'block');">Hide trace</a>)<pre><?php echo print_r($this->_tpl_vars['exception']->getTrace()); ?>
</pre></div>