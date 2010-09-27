<?php /* Smarty version 2.6.18, created on 2010-09-17 14:58:58
         compiled from mydoktervanwacht_delete.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'mydoktervanwacht_delete.tpl', 11, false),array('function', 'ajaxrequest', 'mydoktervanwacht_delete.tpl', 33, false),)), $this); ?>
<p>
Bent u zeker dat u dit wilt verwijderen?
</p>
<p>
	<table>
		<tr>
			<td>			
				<strong>Start: </strong>
			</td>
			<td>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['dokter']->getStart())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d/%m/%Y - %H:%M:%S") : smarty_modifier_date_format($_tmp, "%d/%m/%Y - %H:%M:%S")); ?>

			</td>
		</tr>
		<tr>
			<td>			
				<strong>Stop: </strong>
			</td>
			<td>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['dokter']->getStop())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d/%m/%Y - %H:%M:%S") : smarty_modifier_date_format($_tmp, "%d/%m/%Y - %H:%M:%S")); ?>

			</td>
		</tr>
		<tr>
			<td>			
				<strong>Dokter: </strong>
			</td>
			<td>
				Dr. <?php echo $this->_tpl_vars['dokter']->getNaam(); ?>
 <?php echo $this->_tpl_vars['dokter']->getVoornaam(); ?>

			</td>
		</tr>
	</table>
</p>
<p class="formbuttons">
	<input type="button" value="Verwijderen" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['yes']), $this);?>
"/> &nbsp;&nbsp;&nbsp;
	<input type="button" value="Terug" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['no']), $this);?>
"/>
</p>
<br />
<br />