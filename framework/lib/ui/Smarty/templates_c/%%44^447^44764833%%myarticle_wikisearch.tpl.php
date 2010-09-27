<?php /* Smarty version 2.6.18, created on 2010-05-06 08:56:15
         compiled from myarticle_wikisearch.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxrequest', 'myarticle_wikisearch.tpl', 3, false),)), $this); ?>
<div style="position: relative;">
<h1>Wiki search</h1>
<div style="position: absolute; right: 0px; top: 0px;"><a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['closerequest']), $this);?>
">Sluiten</a></div>
<div class="headerline">&nbsp;</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygrid.tpl", 'smarty_include_vars' => array('grid' => $this->_tpl_vars['grid'],'columns' => "array('Werktitel' => 'alias', 'Aangemaakt door' => 'articleauthorname', 'Aanmaakdatum' => array('column' => 'articlecreationdate','modifier' => 'date_format:\"%H:%M - %d/%m/%Y\"'))")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>