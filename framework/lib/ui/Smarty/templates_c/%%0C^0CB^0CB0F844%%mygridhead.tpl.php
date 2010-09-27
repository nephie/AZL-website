<?php /* Smarty version 2.6.18, created on 2010-05-03 15:42:34
         compiled from mygridhead.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'browser_is', 'mygridhead.tpl', 9, false),array('function', 'ajaxrequest', 'mygridhead.tpl', 26, false),array('function', 'math', 'mygridhead.tpl', 45, false),)), $this); ?>
<?php $this->assign('order', $this->_tpl_vars['grid']->getOrder()); ?>
<?php if (is_array ( $this->_tpl_vars['order'] )): ?>
	<?php $this->assign('orderfield', $this->_tpl_vars['order']['fields']['0']); ?>
	<?php $this->assign('ordertype', $this->_tpl_vars['order']['type']); ?>
<?php else: ?>
	<?php $this->assign('orderfield', ''); ?>
	<?php $this->assign('ordertype', ''); ?>
<?php endif; ?>
<?php echo smarty_function_browser_is(array('vendor' => 'ie','majorversion' => 6,'assign' => 'ie6'), $this);?>

<?php echo smarty_function_browser_is(array('vendor' => 'ie','maxversion' => 7,'assign' => 'ie'), $this);?>


<?php if ($this->_tpl_vars['grid']->isEditAllowed() || $this->_tpl_vars['grid']->isAddAllowed() || $this->_tpl_vars['grid']->isDeleteAllowed()): ?>
	<?php $this->assign('extracols', 70); ?>
<?php else: ?>
	<?php $this->assign('extracols', 20); ?>
<?php endif; ?>


<thead>
<tr class="gridhead">
	<?php if ($this->_tpl_vars['grid']->isEditAllowed() || $this->_tpl_vars['grid']->isAddAllowed() || $this->_tpl_vars['grid']->isDeleteAllowed()): ?>
	<th class="gridadd" style="vertical-align: middle; width: 50px; text-align:left; position: relative;">
		<?php if ($this->_tpl_vars['grid']->isAddAllowed()): ?>
		<?php $this->assign('addrequest', $this->_tpl_vars['grid']->getRequest('-add-')); ?>
			<?php if ($this->_tpl_vars['addrequest'] instanceof ajaxrequest): ?>
				<a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['addrequest']), $this);?>
">
					<span><img src="files/images/add.png" title="Toevoegen"  id="addbutton"/></span>
				</a>
			<?php endif; ?>
		<?php endif; ?>
	</th>
	<?php endif; ?>


	<?php $_from = $this->_tpl_vars['grid']->getColumn(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['head'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['head']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['column']):
        $this->_foreach['head']['iteration']++;
?>

	<?php if (is_array ( $this->_tpl_vars['column'] )): ?>
		<?php $this->assign('colname', $this->_tpl_vars['column']['column']); ?>
	<?php else: ?>
		<?php $this->assign('colname', $this->_tpl_vars['column']); ?>
	<?php endif; ?>

	<?php $this->assign('colorderrequest', $this->_tpl_vars['grid']->getSetorderrequest($this->_tpl_vars['colname'])); ?>

	<?php echo smarty_function_math(array('equation' => "(x - z)/ y",'x' => 727,'y' => $this->_foreach['head']['total'],'z' => $this->_tpl_vars['extracols'],'assign' => 'colwidth'), $this);?>


	<th style="vertical-align: middle; <?php if ($this->_tpl_vars['ie']): ?>width: <?php echo $this->_tpl_vars['colwidth']; ?>
px;<?php endif; ?>">
	<?php if (! in_array ( $this->_tpl_vars['colname'] , $this->_tpl_vars['grid']->getNosortfield() )): ?><a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['colorderrequest']), $this);?>
"><?php endif; ?>
		<?php if ($this->_tpl_vars['key'] != ''): ?>
			<?php echo $this->_tpl_vars['key']; ?>

		<?php else: ?>
			<?php echo $this->_tpl_vars['column']; ?>

		<?php endif; ?>
		<?php if (! in_array ( $this->_tpl_vars['colname'] , $this->_tpl_vars['grid']->getNosortfield() )): ?></a><?php endif; ?>


		<?php if ($this->_tpl_vars['ie6']): ?>
			<?php $this->assign('ext', 'gif'); ?>
		<?php else: ?>
			<?php $this->assign('ext', 'png'); ?>
		<?php endif; ?>

		<?php if ($this->_tpl_vars['orderfield'] == $this->_tpl_vars['colname']): ?>
			<?php if ($this->_tpl_vars['ordertype'] == 'ASC'): ?>
				<img src="files/images/triangle_up_white.<?php echo $this->_tpl_vars['ext']; ?>
">
			<?php else: ?>
				<img src="files/images/triangle_down_white.<?php echo $this->_tpl_vars['ext']; ?>
">
			<?php endif; ?>
		<?php else: ?>
			<img src="files/images/triangle_none.<?php echo $this->_tpl_vars['ext']; ?>
">
		<?php endif; ?>


	</th>
	<?php endforeach; endif; unset($_from); ?>
	<?php if ($this->_tpl_vars['grid']->getOrderfield() != ''): ?>
	<th>
		<?php $this->assign('colorderrequest', $this->_tpl_vars['grid']->getSetorderrequest($this->_tpl_vars['grid']->getOrderfield())); ?>
		<a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['colorderrequest']), $this);?>
">
			Orde
		</a>

		<?php echo smarty_function_browser_is(array('vendor' => 'ie','majorversion' => 6,'assign' => 'ie6'), $this);?>

		<?php if ($this->_tpl_vars['ie6']): ?>
			<?php $this->assign('ext', 'gif'); ?>
		<?php else: ?>
			<?php $this->assign('ext', 'png'); ?>
		<?php endif; ?>

		<?php if ($this->_tpl_vars['orderfield'] == $this->_tpl_vars['grid']->getOrderfield()): ?>
			<?php if ($this->_tpl_vars['ordertype'] == 'ASC'): ?>
				<img src="files/images/triangle_up_white.<?php echo $this->_tpl_vars['ext']; ?>
">
			<?php else: ?>
				<img src="files/images/triangle_down_white.<?php echo $this->_tpl_vars['ext']; ?>
">
			<?php endif; ?>
		<?php else: ?>
			<img src="files/images/triangle_none.<?php echo $this->_tpl_vars['ext']; ?>
">
		<?php endif; ?>
	</th>
	<?php endif; ?>

</tr>
</thead>