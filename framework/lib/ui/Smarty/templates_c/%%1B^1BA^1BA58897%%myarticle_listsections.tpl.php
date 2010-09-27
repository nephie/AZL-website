<?php /* Smarty version 2.6.18, created on 2010-04-29 09:54:08
         compiled from myarticle_listsections.tpl */ ?>
<h1>Secties</h1>
<div class="headerline">&nbsp;</div>
<p>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygrid.tpl", 'smarty_include_vars' => array('grid' => $this->_tpl_vars['grid'],'columns' => "array('Sectie' => 'name')")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</p>
<h1>Algemene rechten voor secties</h1>
<div class="headerline">&nbsp;</div>
<p>
<div id="acllist_listsections"></div>
</p>