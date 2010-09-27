<?php /* Smarty version 2.6.18, created on 2010-05-03 15:52:10
         compiled from mygridrows.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'mygridrows.tpl', 4, false),array('function', 'ajaxrequest', 'mygridrows.tpl', 12, false),array('function', 'eval', 'mygridrows.tpl', 50, false),)), $this); ?>
<tbody>
<?php if (count ( $this->_tpl_vars['grid']->getrow() ) > 0): ?>
	<?php $_from = $this->_tpl_vars['grid']->getRow(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['rows'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['rows']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['row']):
        $this->_foreach['rows']['iteration']++;
?>
		<?php echo smarty_function_cycle(array('values' => "gridrow_A,gridrow_B",'assign' => 'rowcycle'), $this);?>

		<tr class="gridrow <?php echo $this->_tpl_vars['rowcycle']; ?>
" id="gridrow_<?php echo $this->_tpl_vars['grid']->getId(); ?>
_<?php echo $this->_tpl_vars['row']->getId(); ?>
">
			<?php if ($this->_tpl_vars['grid']->isEditAllowed() || $this->_tpl_vars['grid']->isAddAllowed() || $this->_tpl_vars['grid']->isDeleteAllowed()): ?>
				<?php $this->assign('editcol', 'true'); ?>
				<td  class="gridedit"  style="text-align: left;">
					<?php if ($this->_tpl_vars['grid']->isEditAllowed()): ?>
						<?php $this->assign('editrequest', $this->_tpl_vars['grid']->getRequest('-edit-',$this->_tpl_vars['row'])); ?>
						<?php if ($this->_tpl_vars['editrequest'] instanceof ajaxrequest): ?>
							<a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['editrequest']), $this);?>
">
						<img src="files/images/edit_<?php echo $this->_tpl_vars['rowcycle']; ?>
.png" title="Aanpassen"/>
							</a>
						<?php endif; ?>
					<?php endif; ?>
					<?php if ($this->_tpl_vars['grid']->isDeleteAllowed()): ?>
					<?php $this->assign('deleterequest', $this->_tpl_vars['grid']->getRequest('-delete-',$this->_tpl_vars['row'])); ?>
						<?php if ($this->_tpl_vars['deleterequest'] instanceof ajaxrequest): ?>
							<a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['deleterequest']), $this);?>
">

						<img src="files/images/delete_<?php echo $this->_tpl_vars['rowcycle']; ?>
.png" title="Verwijderen"/>

							</a>
						<?php endif; ?>
					<?php endif; ?>
				</td>
			<?php else: ?>
				<?php $this->assign('editcol', 'false'); ?>
			<?php endif; ?>


			<?php $_from = $this->_tpl_vars['grid']->getColumn(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['colforeach'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['colforeach']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['column']):
        $this->_foreach['colforeach']['iteration']++;
?>
					<?php if (($this->_foreach['colforeach']['iteration'] <= 1) && $this->_tpl_vars['editcol'] == 'false'): ?>
						<?php $this->assign('padding', 'style= "padding-left: 3px;"'); ?>
					<?php else: ?>
						<?php $this->assign('padding', ""); ?>
					<?php endif; ?>

					<?php if (is_array ( $this->_tpl_vars['column'] )): ?>
						<?php if (isset ( $this->_tpl_vars['column']['width'] )): ?>
				<td <?php echo $this->_tpl_vars['padding']; ?>
 width="<?php echo $this->_tpl_vars['column']['width']; ?>
">
						<?php else: ?>
				<td <?php echo $this->_tpl_vars['padding']; ?>
>
						<?php endif; ?>
						<?php if ($this->_tpl_vars['row']->_get($this->_tpl_vars['column']['column']) != ''): ?>
							<?php ob_start(); ?>
								{"<?php echo $this->_tpl_vars['row']->_get($this->_tpl_vars['column']['column']); ?>
"|<?php echo $this->_tpl_vars['column']['modifier']; ?>
}
							<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('colwithmod', ob_get_contents());ob_end_clean(); ?>
							<?php echo smarty_function_eval(array('var' => $this->_tpl_vars['colwithmod']), $this);?>

						<?php endif; ?>
					<?php else: ?>
				<td <?php echo $this->_tpl_vars['padding']; ?>
>
						<?php $this->assign('colrequest', $this->_tpl_vars['grid']->getRequest($this->_tpl_vars['column'],$this->_tpl_vars['row'])); ?>
						<?php if ($this->_tpl_vars['colrequest'] instanceof ajaxrequest): ?>
						<a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['colrequest']), $this);?>
">
						<?php endif; ?>

						<?php echo $this->_tpl_vars['row']->_get($this->_tpl_vars['column']); ?>


						<?php if ($this->_tpl_vars['colrequest'] instanceof ajaxrequest): ?>
						</a>
						<?php endif; ?>
					<?php endif; ?>
				</td>
			<?php endforeach; endif; unset($_from); ?>
			<?php if ($this->_tpl_vars['grid']->getOrderfield() != ''): ?>
				<td>
					<?php $this->assign('setobjectorderrequest', $this->_tpl_vars['grid']->getSetobjectorderrequest($this->_tpl_vars['row']->getId())); ?>
					<a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['setobjectorderrequest']), $this);?>
">
						<?php echo $this->_tpl_vars['row']->_get($this->_tpl_vars['grid']->getOrderfield()); ?>

					</a>
				</td>
			<?php endif; ?>

		</tr>
	<?php endforeach; endif; unset($_from); ?>
<?php else: ?>
<tr>
	<td colspan="999">
		Deze lijst bevat geen items.
	</td>
</tr>
<?php endif; ?>
</tbody>