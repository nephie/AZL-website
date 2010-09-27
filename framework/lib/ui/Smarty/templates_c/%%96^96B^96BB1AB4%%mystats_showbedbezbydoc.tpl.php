<?php /* Smarty version 2.6.18, created on 2010-06-22 09:48:57
         compiled from mystats_showbedbezbydoc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'mystats_showbedbezbydoc.tpl', 19, false),)), $this); ?>
<h1>Statistieken bedbezetting: Per dokter</h1>
<div class="headerline">&nbsp;</div>
<p>
<table class="grid">
	<tr class="gridhead">
		<th>
			Dokter
		</th>
		<?php $_from = $this->_tpl_vars['diensten']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['dienstnr']):
?>
		<th>
			<?php echo $this->_tpl_vars['dienstnr']; ?>

		</th>
		<?php endforeach; endif; unset($_from); ?>
		<th>
			Totaal
		</th>
	</tr>
<?php $_from = $this->_tpl_vars['stats']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ognummer'] => $this->_tpl_vars['dokter']):
?>
<?php echo smarty_function_cycle(array('values' => "gridrow_A,gridrow_B",'assign' => 'rowcycle'), $this);?>

	<tr class="gridrow <?php echo $this->_tpl_vars['rowcycle']; ?>
">
		<td>
			<?php if ($this->_tpl_vars['dokter']['naam'] != ''): ?>
				<?php echo $this->_tpl_vars['dokter']['naam']; ?>

			<?php else: ?>
				<?php echo $this->_tpl_vars['ognummer']; ?>

			<?php endif; ?>
		</td>
		<?php $_from = $this->_tpl_vars['diensten']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['dienstnr']):
?>
		<td>
			<?php if ($this->_tpl_vars['dokter'][$this->_tpl_vars['dienstnr']] > 0): ?>
				<strong><?php echo $this->_tpl_vars['dokter'][$this->_tpl_vars['dienstnr']]; ?>
</strong>
			<?php else: ?>
				-
			<?php endif; ?>
		</td>
		<?php endforeach; endif; unset($_from); ?>
		<td>
			<?php echo $this->_tpl_vars['dokter']['all']; ?>

		</td>
	</tr>
<?php endforeach; endif; unset($_from); ?>
	<tr class="printgridfoot">
		<td>
			Totaal
		</td>
		<?php $_from = $this->_tpl_vars['diensten']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['dienstnr']):
?>
		<td>
			<?php if ($this->_tpl_vars['ve'][$this->_tpl_vars['dienstnr']] > 0): ?>
				<?php echo $this->_tpl_vars['ve'][$this->_tpl_vars['dienstnr']]; ?>

			<?php else: ?>
				-
			<?php endif; ?>
		</td>
		<?php endforeach; endif; unset($_from); ?>
		<td>
			<?php echo $this->_tpl_vars['ve']['all']; ?>

		</td>
	</tr>
</table>
</p>