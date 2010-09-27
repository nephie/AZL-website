<?php /* Smarty version 2.6.18, created on 2010-04-28 11:21:23
         compiled from selectField.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxform', 'selectField.tpl', 8, false),)), $this); ?>
<?php if ($this->_tpl_vars['field']->getMultiple()): ?>
	<?php $this->assign('multiple', 'multiple="multiple"'); ?>
	<?php $this->assign('class', 'class="multipleSelect"'); ?>
<?php else: ?>
	<?php $this->assign('multiple', ""); ?>
	<?php $this->assign('class', ""); ?>
<?php endif; ?>
<select name="<?php echo $this->_tpl_vars['field']->getName(); ?>
<?php if ($this->_tpl_vars['field']->getMultiple()): ?>[]<?php endif; ?>" title="<?php echo $this->_tpl_vars['field']->getExtra(); ?>
"  id="<?php echo $this->_tpl_vars['field']->getId(); ?>
" <?php echo $this->_tpl_vars['multiple']; ?>
 <?php echo $this->_tpl_vars['class']; ?>
 <?php if ($this->_tpl_vars['form']->isPhased()): ?> onChange="<?php echo smarty_function_ajaxform(array('form' => $this->_tpl_vars['form'],'notfinal' => true,'field' => $this->_tpl_vars['field']->getName()), $this);?>
"<?php endif; ?>>
	<?php $this->assign('options', $this->_tpl_vars['field']->getOption()); ?>
	<?php $this->assign('optgroups', $this->_tpl_vars['field']->getOptgroup()); ?>

	<?php unset($this->_sections['selectloop']);
$this->_sections['selectloop']['name'] = 'selectloop';
$this->_sections['selectloop']['loop'] = is_array($_loop=$this->_tpl_vars['field']->getIndex()) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['selectloop']['show'] = true;
$this->_sections['selectloop']['max'] = $this->_sections['selectloop']['loop'];
$this->_sections['selectloop']['step'] = 1;
$this->_sections['selectloop']['start'] = $this->_sections['selectloop']['step'] > 0 ? 0 : $this->_sections['selectloop']['loop']-1;
if ($this->_sections['selectloop']['show']) {
    $this->_sections['selectloop']['total'] = $this->_sections['selectloop']['loop'];
    if ($this->_sections['selectloop']['total'] == 0)
        $this->_sections['selectloop']['show'] = false;
} else
    $this->_sections['selectloop']['total'] = 0;
if ($this->_sections['selectloop']['show']):

            for ($this->_sections['selectloop']['index'] = $this->_sections['selectloop']['start'], $this->_sections['selectloop']['iteration'] = 1;
                 $this->_sections['selectloop']['iteration'] <= $this->_sections['selectloop']['total'];
                 $this->_sections['selectloop']['index'] += $this->_sections['selectloop']['step'], $this->_sections['selectloop']['iteration']++):
$this->_sections['selectloop']['rownum'] = $this->_sections['selectloop']['iteration'];
$this->_sections['selectloop']['index_prev'] = $this->_sections['selectloop']['index'] - $this->_sections['selectloop']['step'];
$this->_sections['selectloop']['index_next'] = $this->_sections['selectloop']['index'] + $this->_sections['selectloop']['step'];
$this->_sections['selectloop']['first']      = ($this->_sections['selectloop']['iteration'] == 1);
$this->_sections['selectloop']['last']       = ($this->_sections['selectloop']['iteration'] == $this->_sections['selectloop']['total']);
?>
		<?php if (isset ( $this->_tpl_vars['options'][$this->_sections['selectloop']['index']] )): ?>
			<?php $this->assign('option', $this->_tpl_vars['options'][$this->_sections['selectloop']['index']]); ?>
			<?php if ($this->_tpl_vars['option']->getSelected()): ?>
				<?php $this->assign('selected', 'selected="selected"'); ?>
			<?php else: ?>
				<?php $this->assign('selected', ""); ?>
			<?php endif; ?>
			<option class="tips" label="<?php echo $this->_tpl_vars['option']->getName(); ?>
" title="<?php echo $this->_tpl_vars['option']->getExtra(); ?>
" value="<?php echo $this->_tpl_vars['option']->getValue(); ?>
" <?php echo $this->_tpl_vars['selected']; ?>
><?php echo $this->_tpl_vars['option']->getName(); ?>
</option>
		<?php endif; ?>

		<?php if (isset ( $this->_tpl_vars['optgroups'][$this->_sections['selectloop']['index']] )): ?>
			<?php $this->assign('optgroup', $this->_tpl_vars['optgroups'][$this->_sections['selectloop']['index']]); ?>
			<optgroup label="<?php echo $this->_tpl_vars['optgroup']->getName(); ?>
">
				<?php $_from = $this->_tpl_vars['optgroup']->getOption(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['option']):
?>
					<?php if ($this->_tpl_vars['option']->getSelected()): ?>
						<?php $this->assign('selected', 'selected="selected"'); ?>
					<?php else: ?>
						<?php $this->assign('selected', ""); ?>
					<?php endif; ?>
					<option label="<?php echo $this->_tpl_vars['option']->getName(); ?>
" value="<?php echo $this->_tpl_vars['option']->getValue(); ?>
" <?php echo $this->_tpl_vars['selected']; ?>
><?php echo $this->_tpl_vars['option']->getName(); ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
			</optgroup>
		<?php endif; ?>
	<?php endfor; endif; ?>
</select>