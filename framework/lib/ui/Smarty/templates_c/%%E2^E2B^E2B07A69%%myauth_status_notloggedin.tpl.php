<?php /* Smarty version 2.6.18, created on 2010-04-28 22:05:50
         compiled from myauth_status_notloggedin.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'pagerequest', 'myauth_status_notloggedin.tpl', 1, false),)), $this); ?>
<a href="<?php echo smarty_function_pagerequest(array('request' => $this->_tpl_vars['loginRequest']), $this);?>
">Inloggen</a>