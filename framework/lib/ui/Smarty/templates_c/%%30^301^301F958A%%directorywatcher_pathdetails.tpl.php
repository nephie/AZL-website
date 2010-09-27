<?php /* Smarty version 2.6.18, created on 2010-04-29 09:05:56
         compiled from directorywatcher_pathdetails.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxrequest', 'directorywatcher_pathdetails.tpl', 2, false),)), $this); ?>
<h1>Details voor <a href="file:///<?php echo $this->_tpl_vars['current']->getPath(); ?>
"><?php echo $this->_tpl_vars['current']->getPath(); ?>
</a></h1>
<div style="position: absolute; right: 0px; top: 0px;"><a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['closerequest']), $this);?>
">Sluiten</a></div>
<div class="headerline">&nbsp;</div>
<p>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygrid.tpl", 'smarty_include_vars' => array('grid' => ($this->_tpl_vars['history']),'columns' => "array('Aantal bestanden' => 'numfiles','Laatst aangepast' => array('column' => 'lastfiletime', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"' , 'width' => '130px'), 'Oudste bestand' => array('column' => 'oldestfiletime', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"' , 'width' => '130px'), 'Bestaat' => 'exists', 'Meldingstijd' => array('column' => 'reporttime', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"' , 'width' => '130px'), 'Status' => 'status')")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</p>
<h1>Treshold voor <?php echo $this->_tpl_vars['current']->getPath(); ?>
</h1>
<div class="headerline">&nbsp;</div>
<p>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygrid.tpl", 'smarty_include_vars' => array('grid' => $this->_tpl_vars['tresholdgrid'],'columns' => "array('path' => 'path', 'Aantal bestanden' => 'numfiles','Laatst aangepast' => 'lastfiletime', 'Oudste bestand' => 'oldestfiletime', 'Bestaat' => 'exists', 'Mail' => 'mail', 'Mail naar' => 'mailto')")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</p>