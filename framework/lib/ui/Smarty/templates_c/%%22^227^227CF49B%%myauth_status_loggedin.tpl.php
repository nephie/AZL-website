<?php /* Smarty version 2.6.18, created on 2010-04-28 11:21:23
         compiled from myauth_status_loggedin.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxrequest', 'myauth_status_loggedin.tpl', 1, false),)), $this); ?>
U bent aangemeld als <?php echo $this->_tpl_vars['currentuser']->getName(); ?>
 (<a href="#" onclick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['logoutRequest']), $this);?>
">Afmelden</a>)