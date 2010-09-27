<?php /* Smarty version 2.6.18, created on 2010-05-04 16:28:28
         compiled from mygrid_setobjectorder.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxrequest', 'mygrid_setobjectorder.tpl', 2, false),)), $this); ?>
<h1>Orde aanpassen</h1>
<div style="position: absolute; right: 0px; top: 0px;"><a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['closeextra']), $this);?>
">Sluiten</a></div></a></div>
<div class="headerline">&nbsp;</div>
<p>
	Geef hieronder de nieuwe plaats in.
</p>
<p>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form.tpl", 'smarty_include_vars' => array('form' => $this->_tpl_vars['form'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</p>