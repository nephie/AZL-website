<?php /* Smarty version 2.6.18, created on 2010-04-28 11:21:53
         compiled from mygrid.tpl */ ?>
<?php if ($this->_tpl_vars['grid'] instanceof mygrid): ?>

<div class="gridcontainer" id="<?php echo $this->_tpl_vars['grid']->getId(); ?>
">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygridstructure.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
<br />
<br />
<div class="gridextra" style="position:relative;" id="gridextra_<?php echo $this->_tpl_vars['grid']->getId(); ?>
">

</div>

<?php endif; ?>