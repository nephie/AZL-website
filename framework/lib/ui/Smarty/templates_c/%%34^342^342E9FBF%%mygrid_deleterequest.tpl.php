<?php /* Smarty version 2.6.18, created on 2010-04-30 11:55:45
         compiled from mygrid_deleterequest.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxrequest', 'mygrid_deleterequest.tpl', 2, false),)), $this); ?>
<h1><?php echo $this->_tpl_vars['title']; ?>
</h1>
<div style="position: absolute; right: 0px; top: 0px;"><a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['closerequest']), $this);?>
">Sluiten</a></div>
<div class="headerline">&nbsp;</div>
<?php echo $this->_tpl_vars['content']; ?>

<a href="javascript:void(0);" onclick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['ja']), $this);?>
">Verwijderen</a>