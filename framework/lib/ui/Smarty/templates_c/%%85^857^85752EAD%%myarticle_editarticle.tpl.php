<?php /* Smarty version 2.6.18, created on 2010-04-30 09:14:08
         compiled from myarticle_editarticle.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form.tpl", 'smarty_include_vars' => array('form' => $this->_tpl_vars['aliasform'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<h1>Versies</h1>
<div class="headerline">&nbsp;</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygrid.tpl", 'smarty_include_vars' => array('grid' => $this->_tpl_vars['grid'],'columns' => "array('Status' => 'state', 'Titel' => 'title', 'Aangemaakt door' => 'authorname' , 'Aanmaakdatum' => array('column' => 'creationdate','modifier' => 'date_format:\"%H:%M - %d/%m/%Y\"') )")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['sectiongrid'] instanceof mygrid): ?>
<h1>Gelinkte secties</h1>
<div class="headerline">&nbsp;</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygrid.tpl", 'smarty_include_vars' => array('grid' => $this->_tpl_vars['sectiongrid'],'columns' => "array('Sectie' => 'sectionname')")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['acllist'] != ''): ?>
<h1>Rechten voor artikel <?php echo $this->_tpl_vars['article']->getAlias(); ?>
</h1>
<div class="headerline">&nbsp;</div>
<p>
<div id="acllist_article"><?php echo $this->_tpl_vars['acllist']; ?>
</div>
</p>
<?php endif; ?>