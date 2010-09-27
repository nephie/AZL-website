<?php /* Smarty version 2.6.18, created on 2010-04-28 11:21:37
         compiled from confirmselectField.tpl */ ?>
<?php $this->assign('options', $this->_tpl_vars['field']->getOption()); ?>

<?php if ($this->_tpl_vars['field']->getMultiple()): ?>
<ul>
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
				<li><?php echo $this->_tpl_vars['option']->getName(); ?>
</li>
			<?php endif; ?>

		<?php endif; ?>
	<?php endfor; endif; ?>
</ul>
<?php else: ?>
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
				<?php echo $this->_tpl_vars['option']->getName(); ?>

			<?php endif; ?>

		<?php endif; ?>
	<?php endfor; endif; ?>
<?php endif; ?>

	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "hiddenField.tpl", 'smarty_include_vars' => array('field' => $this->_tpl_vars['field'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>