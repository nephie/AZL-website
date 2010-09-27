<?php /* Smarty version 2.6.18, created on 2010-04-28 11:21:53
         compiled from flash.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxrequest', 'flash.tpl', 3, false),)), $this); ?>
<div class="<?php echo $this->_tpl_vars['type']; ?>
flash" id="<?php echo $this->_tpl_vars['name']; ?>
">
<div class="popupDestroy">
	<a href="javascript:;"onclick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['destroyRequest']), $this);?>
"><span>Sluiten</span></a>
</div>
<?php echo $this->_tpl_vars['content']; ?>

</div>