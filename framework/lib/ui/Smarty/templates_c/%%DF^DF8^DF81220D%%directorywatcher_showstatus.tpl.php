<?php /* Smarty version 2.6.18, created on 2010-04-29 09:05:17
         compiled from directorywatcher_showstatus.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxrequest', 'directorywatcher_showstatus.tpl', 3, false),)), $this); ?>
<div style="position: relative;">
<h1>DirectoryWatcher: Fouten</h1>
<div style="position: absolute; right: 0px; top: 0px;">Ongecontroleerd: <?php echo $this->_tpl_vars['unprocessedcount']; ?>
 (<a href="#" onclick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['processrequest']), $this);?>
">nu controleren</a>)</div>
<div class="headerline">&nbsp;</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygrid.tpl", 'smarty_include_vars' => array('grid' => $this->_tpl_vars['errorgrid'],'columns' => "array('Path' => 'path', 'Aantal bestanden' => 'numfiles','Laatst aangepast' => array('column' => 'lastfiletime', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"' , 'width' => '130px'), 'Oudste bestand' => array('column' => 'oldestfiletime', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"' , 'width' => '130px'), 'Bestaat' => 'exists', 'Meldingstijd' => array('column' => 'reporttime', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"' , 'width' => '130px'), 'Status' => 'status')")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<h1>DirectoryWatcher: Alles</h1>
<div class="headerline">&nbsp;</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygrid.tpl", 'smarty_include_vars' => array('grid' => $this->_tpl_vars['grid'],'columns' => "array('Path' => 'path', 'Aantal bestanden' => 'numfiles','Laatst aangepast' => array('column' => 'lastfiletime', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"' , 'width' => '130px'), 'Oudste bestand' => array('column' => 'oldestfiletime', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"' , 'width' => '130px'), 'Bestaat' => 'exists', 'Meldingstijd' => array('column' => 'reporttime', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"' , 'width' => '130px'), 'Status' => 'status')")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<h1>Standaard Treshold</h1>
<div class="headerline">&nbsp;</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygrid.tpl", 'smarty_include_vars' => array('grid' => $this->_tpl_vars['deftresholdgrid'],'columns' => "array('Aantal bestanden' => 'numfiles','Laatst aangepast' => 'lastfiletime', 'Oudste bestand' => 'oldestfiletime', 'Bestaat' => 'exists', 'Mail' => 'mail', 'Mail naar' => 'mailto')")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>