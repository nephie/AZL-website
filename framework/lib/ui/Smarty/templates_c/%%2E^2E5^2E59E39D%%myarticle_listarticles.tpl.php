<?php /* Smarty version 2.6.18, created on 2010-04-29 09:54:00
         compiled from myarticle_listarticles.tpl */ ?>
<h1>Artikels</h1>
<div class="headerline">&nbsp;</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygrid.tpl", 'smarty_include_vars' => array('grid' => $this->_tpl_vars['grid'],'columns' => "array('Werktitel' => 'alias', 'Aangemaakt door' => 'authorname', 'Aanmaakdatum' => array('column' => 'creationdate','modifier' => 'date_format:\"%H:%M - %d/%m/%Y\"'))")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php if ($this->_tpl_vars['acllist'] != ''): ?>
<h1>Algemene rechten voor artikels</h1>
<div class="headerline">&nbsp;</div>
<p>
<div id="acllist_listarticles"><?php echo $this->_tpl_vars['acllist']; ?>
</div>
</p>
<?php endif; ?>