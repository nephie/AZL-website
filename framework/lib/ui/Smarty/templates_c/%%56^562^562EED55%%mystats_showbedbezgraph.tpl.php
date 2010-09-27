<?php /* Smarty version 2.6.18, created on 2010-05-10 15:05:28
         compiled from mystats_showbedbezgraph.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxrequest', 'mystats_showbedbezgraph.tpl', 7, false),)), $this); ?>
<p>
<br />
<br />
<div style="position: relative">
<h1>Historiek bedbezetting </h1>
<div class="headerline">&nbsp;</div>
<div style="position: absolute; right: 0px; top: 0px;"><a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['closerequest']), $this);?>
">Sluiten</a></div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form.tpl", 'smarty_include_vars' => array('form' => $this->_tpl_vars['form'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<img src="bedbezgraph.php?dienst=<?php echo $this->_tpl_vars['dienst']; ?>
&starttime=<?php echo $this->_tpl_vars['starttime']; ?>
&endtime=<?php echo $this->_tpl_vars['endtime']; ?>
" width="600" height="400" />
</div>
</p>