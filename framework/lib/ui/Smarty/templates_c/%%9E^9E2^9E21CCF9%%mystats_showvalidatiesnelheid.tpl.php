<?php /* Smarty version 2.6.18, created on 2010-05-25 10:14:44
         compiled from mystats_showvalidatiesnelheid.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'mystats_showvalidatiesnelheid.tpl', 1, false),array('modifier', 'number_format', 'mystats_showvalidatiesnelheid.tpl', 41, false),array('function', 'cycle', 'mystats_showvalidatiesnelheid.tpl', 32, false),array('function', 'math', 'mystats_showvalidatiesnelheid.tpl', 40, false),)), $this); ?>
<h1>Statistieken validatiesnelheid: sinds <?php echo ((is_array($_tmp=$this->_tpl_vars['startthis'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d/%m/%Y") : smarty_modifier_date_format($_tmp, "%d/%m/%Y")); ?>
</h1>
<div class="headerline">&nbsp;</div>
<p>
<table class="grid">
	<tr class="gridhead">
		<th>
			Dokter
		</th>
		<th>
			Gemiddelde
		</th>
		<th>
			Genorm. gem.
		</th>
		<th>
			# geval.
		</th>
		<th>
			# niet geval.
		</th>
		<th>
			% geval.
		</th>
		<th>
			Min.
		</th>
		<th>
			Max.
		</th>
	</tr>
<?php $_from = $this->_tpl_vars['avg']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['avg'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['avg']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['row']):
        $this->_foreach['avg']['iteration']++;
?>
<?php echo smarty_function_cycle(array('values' => "gridrow_A,gridrow_B",'assign' => 'rowcycle'), $this);?>


	<tr class="gridrow <?php echo $this->_tpl_vars['rowcycle']; ?>
">
		<td style="padding-left: 5px;">
			<?php echo $this->_tpl_vars['row']['validerendeArtsNaam']; ?>

		</td>
		<td>
			<?php if ($this->_tpl_vars['row']['avg'] != ''): ?>
				<?php echo smarty_function_math(array('equation' => "x / (60 * 60)",'x' => $this->_tpl_vars['row']['avg'],'format' => "%.2f",'assign' => 'avg'), $this);?>

				<?php echo ((is_array($_tmp=$this->_tpl_vars['avg'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ',', '') : number_format($_tmp, 2, ',', '')); ?>

			<?php else: ?>

			<?php endif; ?>
		 </td>
		 <td>
			<?php if ($this->_tpl_vars['row']['normavg'] != ''): ?>
				<?php echo smarty_function_math(array('equation' => "x / (60 * 60)",'x' => $this->_tpl_vars['row']['normavg'],'format' => "%.2f",'assign' => 'normavg'), $this);?>

				<?php echo ((is_array($_tmp=$this->_tpl_vars['normavg'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ',', '') : number_format($_tmp, 2, ',', '')); ?>

			<?php else: ?>

			<?php endif; ?>
		 </td>
		 <td>
		 	<?php if ($this->_tpl_vars['row']['total'] != ''): ?>
				<?php echo $this->_tpl_vars['row']['total']; ?>

			<?php else: ?>
				0
			<?php endif; ?>
		 </td>
		 <td>
		 	<?php if ($this->_tpl_vars['row']['notvalidated'] != ''): ?>
				<?php echo $this->_tpl_vars['row']['notvalidated']; ?>

			<?php else: ?>
				0
			<?php endif; ?>
		 </td>
		 <td>
		 	<?php if ($this->_tpl_vars['row']['total'] == '' && $this->_tpl_vars['row']['notvalidated'] == ''): ?>
		 		100
		 	<?php elseif ($this->_tpl_vars['row']['total'] == ''): ?>
		 		0
		 	<?php elseif ($this->_tpl_vars['row']['notvalidated'] == ''): ?>
		 		100
		 	<?php else: ?>
		 		<?php echo smarty_function_math(array('equation' => "(x/(x+y))*100",'x' => $this->_tpl_vars['row']['total'],'y' => $this->_tpl_vars['row']['notvalidated'],'format' => "%d"), $this);?>

		 	<?php endif; ?>
		 	%
		 </td>
		 <td>
		 	<?php if ($this->_tpl_vars['row']['min'] != ''): ?>
				<?php echo smarty_function_math(array('equation' => "x / (60 * 60)",'x' => $this->_tpl_vars['row']['min'],'format' => "%.2f",'assign' => 'min'), $this);?>

				<?php echo ((is_array($_tmp=$this->_tpl_vars['min'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ',', '') : number_format($_tmp, 2, ',', '')); ?>

			<?php else: ?>

			<?php endif; ?>
		 </td>
		 <td>
		 	<?php if ($this->_tpl_vars['row']['max'] != ''): ?>
				<?php echo smarty_function_math(array('equation' => "x / (60 * 60)",'x' => $this->_tpl_vars['row']['max'],'format' => "%.2f",'assign' => 'max'), $this);?>

				<?php echo ((is_array($_tmp=$this->_tpl_vars['max'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ',', '') : number_format($_tmp, 2, ',', '')); ?>

			<?php else: ?>

			<?php endif; ?>
		 </td>
	</tr>
<?php endforeach; endif; unset($_from); ?>
	<tr class="gridfoot">
		<td>

		</td>
		<td>

		</td>
		<td>

		</td>
		<td>

		</td>
		<td>

		</td>
		<td>

		</td>
		<td>

		</td>
		<td>

		</td>
	</tr>
</table>
</p>