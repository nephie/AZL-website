<?php /* Smarty version 2.6.18, created on 2010-04-28 11:21:53
         compiled from mygridstructure.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'string2array', 'mygridstructure.tpl', 1, false),)), $this); ?>
<?php echo smarty_function_string2array(array('string' => $this->_tpl_vars['columns'],'var' => 'columnsarray'), $this);?>

<?php if (count ( $this->_tpl_vars['columnsarray'] ) > 0): ?>
	<?php $this->assign('hack', $this->_tpl_vars['grid']->setColumn($this->_tpl_vars['columnsarray'])); ?>
<?php endif; ?>
<table class="grid">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygridhead.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygridrows.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygridfoot.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</table>