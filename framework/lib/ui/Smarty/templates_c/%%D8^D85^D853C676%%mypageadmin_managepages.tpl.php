<?php /* Smarty version 2.6.18, created on 2010-04-30 11:55:20
         compiled from mypageadmin_managepages.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxrequest', 'mypageadmin_managepages.tpl', 5, false),)), $this); ?>
<h1>
	Pagina beheer
	<?php if ($this->_tpl_vars['currentpage'] instanceof pageObject): ?>
	: 	<?php $_from = $this->_tpl_vars['path']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['path'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['path']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['page']):
        $this->_foreach['path']['iteration']++;
?>
			<a href="#" onclick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['page']['request']), $this);?>
"><?php echo $this->_tpl_vars['page']['page']->getTitle(); ?>

			<?php if (! ($this->_foreach['path']['iteration'] == $this->_foreach['path']['total'])): ?> / <?php endif; ?></a>
		<?php endforeach; endif; unset($_from); ?>
	<?php endif; ?>
</h1>
<div class="headerline">&nbsp;</div>

<?php if ($this->_tpl_vars['currentpage'] instanceof pageObject): ?>
<h2>Titel aanpassen</h2>
<p>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form.tpl", 'smarty_include_vars' => array('form' => $this->_tpl_vars['titleform'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</p>
<?php endif; ?>

<h2>Onderliggende pagina's</h2>
<p>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygrid.tpl", 'smarty_include_vars' => array('grid' => $this->_tpl_vars['grid'],'columns' => "array('Titel' => 'title')")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</p>

<?php if ($this->_tpl_vars['currentpage'] instanceof pageObject): ?>
<h2>Rechten</h2>
<p>
	<?php echo $this->_tpl_vars['acl']; ?>

</p>
<?php endif; ?>

<h1>Gekoppelde modules</h1>
<div class="headerline">&nbsp;</div>
<?php $_from = $this->_tpl_vars['modules']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['areaname'] => $this->_tpl_vars['area']):
?>
<h2><?php echo $this->_tpl_vars['areaname']; ?>
</h2>
<p>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygrid.tpl", 'smarty_include_vars' => array('grid' => $this->_tpl_vars['area'],'columns' => "array('Titel' => 'moduletitle', 'Module' => 'modulename', 'Actie' => 'moduleaction', 'Argumenten' => 'moduleargs')")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</p>
<?php endforeach; endif; unset($_from); ?>