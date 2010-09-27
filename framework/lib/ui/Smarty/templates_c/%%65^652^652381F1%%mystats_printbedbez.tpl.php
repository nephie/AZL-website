<?php /* Smarty version 2.6.18, created on 2010-05-28 08:12:43
         compiled from mystats_printbedbez.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'mystats_printbedbez.tpl', 23, false),array('function', 'math', 'mystats_printbedbez.tpl', 24, false),)), $this); ?>
<table class="grid printtable">
	<tr class="printtablehead">
		<th width="200px">
			Dienst
		</th>
		<th >
			Bezet
		</th>
		<th >
			Totaal
		</th>
		<th >
			%
		</th>
		<th width="200px">
			Vrij
		</th>
		<th width="200px">
			Ontslagen
		</th>
	</tr>
<?php $_from = $this->_tpl_vars['diensten']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['diensten'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['diensten']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['dienstarr']):
        $this->_foreach['diensten']['iteration']++;
?>
	<?php echo smarty_function_cycle(array('values' => "gridrow_A,gridrow_B",'assign' => 'rowcycle'), $this);?>

	<?php echo smarty_function_math(array('equation' => "(bezet / totaal) * 100 ",'bezet' => $this->_tpl_vars['dienstarr']['count'],'totaal' => $this->_tpl_vars['dienstarr']['dienst']->getAantalbedden(),'format' => "%.0f",'assign' => 'procent'), $this);?>


		<tr class="gridrow <?php echo $this->_tpl_vars['rowcycle']; ?>
">
			<?php if ($this->_tpl_vars['dienstarr']['dienst']->getDienstnr() != '007' && $this->_tpl_vars['dienstarr']['dienst']->getDienstnr() != '008' && $this->_tpl_vars['dienstarr']['dienst']->getDienstnr() != '012' && $this->_tpl_vars['dienstarr']['dienst']->getDienstnr() != '009' && $this->_tpl_vars['dienstarr']['dienst']->getDienstnr() != '099' && $this->_tpl_vars['dienstarr']['dienst']->getDienstnr() != '999'): ?>
				<td style="padding:48px 0px ;">
			<?php else: ?>
				<td>
			<?php endif; ?>
				<?php echo $this->_tpl_vars['dienstarr']['dienst']->getName(); ?>

			</td>
			<td >
			 	<?php echo $this->_tpl_vars['dienstarr']['count']; ?>

			 </td>
			 <td>
			 	<?php echo $this->_tpl_vars['dienstarr']['dienst']->getAantalbedden(); ?>

			 </td>
			 <td>



			 	<span <?php echo $this->_tpl_vars['procentcolor']; ?>
>
			 		<?php echo $this->_tpl_vars['procent']; ?>
%
			 	</span>
			 </td>
			 <td>
			 	&nbsp;
			 </td>
			 <td>
			 	&nbsp;
			 </td>
		</tr>
<?php endforeach; endif; unset($_from); ?>
	<tr class="printtablefoot">
		<td>
			Totaal
		</td>
		<td>
			<?php echo $this->_tpl_vars['total']; ?>

		</td>
		<td>
			<?php echo $this->_tpl_vars['totalmax']; ?>

		</td>
		<td>
			<?php echo smarty_function_math(array('equation' => "(bezet / totaal) * 100 ",'bezet' => $this->_tpl_vars['total'],'totaal' => $this->_tpl_vars['totalmax'],'format' => "%.0f",'assign' => 'procent'), $this);?>



		 		<?php echo $this->_tpl_vars['procent']; ?>
%
		</td>
		 <td>
		 	&nbsp;
		 </td>
		 <td>
		 	&nbsp;
		 </td>
	</tr>
</table>