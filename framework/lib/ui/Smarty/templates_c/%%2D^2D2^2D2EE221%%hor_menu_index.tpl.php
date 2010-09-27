<?php /* Smarty version 2.6.18, created on 2010-04-28 11:21:23
         compiled from hor_menu_index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'pagerequest', 'hor_menu_index.tpl', 5, false),)), $this); ?>
<?php if (count ( $this->_tpl_vars['menu'] ) > 1): ?>
<div class="hor_menu"><span class="container">
	<?php $_from = $this->_tpl_vars['menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['hormenu'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['hormenu']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['page']):
        $this->_foreach['hormenu']['iteration']++;
?>
		<span class="hor_menu_span <?php echo $this->_tpl_vars['page']['status']; ?>
">
			<span><a href="<?php echo smarty_function_pagerequest(array('request' => $this->_tpl_vars['page']['page']->getRequest()), $this);?>
"><?php echo $this->_tpl_vars['page']['page']->getTitle(); ?>
</a></span>
		</span>
		<?php if (! ($this->_foreach['hormenu']['iteration'] == $this->_foreach['hormenu']['total'])): ?>
			&nbsp;|&nbsp;
		<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</span>
</div>
<?php endif; ?>