<?php /* Smarty version 2.6.18, created on 2010-04-30 10:11:59
         compiled from myauth_loginform_loggedin.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxrequest', 'myauth_loginform_loggedin.tpl', 5, false),array('function', 'pagerequest', 'myauth_loginform_loggedin.tpl', 6, false),)), $this); ?>
<p>
	U bent aangemeld als <?php echo $this->_tpl_vars['currentuser']->getName(); ?>
. Deze gebruiker heeft echter geen toegang tot deze pagina.
</p>
<p>
	U kunt zich ofwel <a href="#" onclick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['logoutRequest']), $this);?>
">afmelden</a> en met een andere gebruiker inloggen
	of u kunt proberen of u de <a href="<?php echo smarty_function_pagerequest(array('request' => $this->_tpl_vars['defrequest']), $this);?>
">home-pagina</a> kan opvragen.  
</p>