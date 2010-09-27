<?php /* Smarty version 2.6.18, created on 2010-04-28 11:21:37
         compiled from confirmcheckboxgroupField.tpl */ ?>
<?php $_from = $this->_tpl_vars['field']->getBoxes(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['box']):
?>
<strong><?php echo $this->_tpl_vars['box']['name']; ?>
:</strong>
<?php if ($this->_tpl_vars['box']['selected']): ?>
		Ja
		<input type="hidden" name="<?php echo $this->_tpl_vars['field']->getName(); ?>
[]" value="<?php echo $this->_tpl_vars['id']; ?>
" />
<?php else: ?>
		Nee

<?php endif; ?>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php endforeach; endif; unset($_from); ?>