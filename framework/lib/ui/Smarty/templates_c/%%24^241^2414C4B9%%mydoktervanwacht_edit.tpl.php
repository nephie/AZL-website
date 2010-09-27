<?php /* Smarty version 2.6.18, created on 2010-09-20 10:37:46
         compiled from mydoktervanwacht_edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxrequest', 'mydoktervanwacht_edit.tpl', 3, false),array('function', 'ajaxform', 'mydoktervanwacht_edit.tpl', 29, false),array('function', 'cycle', 'mydoktervanwacht_edit.tpl', 80, false),array('modifier', 'date_format', 'mydoktervanwacht_edit.tpl', 86, false),)), $this); ?>
<div style="position: relative">
<h1>Dokter van wacht: <?php echo $this->_tpl_vars['specialisme']->getName(); ?>
</h1>
<div style="position: absolute; right: 0px; top: 0px;"><a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['closerequest']), $this);?>
">Sluiten</a></div>
<div class="headerline">&nbsp;</div>
</div>

<p>
	<?php if ($this->_tpl_vars['form'] instanceof form): ?>
	<form onsubmit="return false;" id="<?php echo $this->_tpl_vars['form']->getId(); ?>
">
	<div class="formfields">
		<table>
		<?php $_from = $this->_tpl_vars['form']->getField(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
		<?php if (! $this->_tpl_vars['field'] instanceof hiddenField): ?>
			<?php if ($this->_tpl_vars['field']->getName() != 'day'): ?>
			<tr>
				<td class="formlabel"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "formfieldlabel.tpl", 'smarty_include_vars' => array('field' => $this->_tpl_vars['field'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
				<td class="formfield"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "formField.tpl", 'smarty_include_vars' => array('field' => $this->_tpl_vars['field'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>

			</tr>
			<?php endif; ?>
		<?php else: ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "formField.tpl", 'smarty_include_vars' => array('field' => $this->_tpl_vars['field'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		<tr>
			<td>&nbsp;</td>
			<td>

				<div class="formbuttons"><input type="submit" value="<?php echo $this->_tpl_vars['form']->getSubmittext(); ?>
" onclick="tinyMCE.triggerSave(false, false);<?php echo smarty_function_ajaxform(array('form' => $this->_tpl_vars['form']), $this);?>
" /> <input type="reset" value="<?php echo $this->_tpl_vars['form']->getResettext(); ?>
" /></div>


				<input type="hidden" id="hidden_form_id" name="hidden_form_id" value="<?php echo $this->_tpl_vars['form']->getId(); ?>
" />
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<div class="formerror" id="formerror_<?php echo $this->_tpl_vars['form']->getId(); ?>
">&nbsp;</div>
			</td>
		</tr>
		</table>
		</div>

<?php echo $this->_tpl_vars['form']->getFocus(); ?>

<?php endif; ?>
</p>
<p>
	<table class="grid">
		<tr class="gridhead">
			<td style="vertical-align: middle; width: 50px; text-align: left; position: relative;" class="gridadd">
					
				</a>
			</td>
			<td>
				Datum
			</td>
			
			<td>
				<table>
				
					<tr>
						<td style="width: 50px;">
							&nbsp;
						</td>
						<td style="width: 75px;">
							Van
						</td>
						<td style="width: 75px;">
							Tot
						</td>
						<td>
							Dokter
						</td>
					</tr>
				
				</table>
			</td>
		</tr>
		<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['day']):
?>
		<?php echo smarty_function_cycle(array('values' => "gridrow_A,gridrow_B",'assign' => 'rowcycle'), $this);?>

		<tr class="gridrow <?php echo $this->_tpl_vars['rowcycle']; ?>
">
			<td>
				
			</td>
			<td>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['day']['start'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d/%m/%Y") : smarty_modifier_date_format($_tmp, "%d/%m/%Y")); ?>

			</td>
			<td>
				<table>
				<?php $_from = $this->_tpl_vars['day']['dokters']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['dokter']):
?>
					<tr>
						<td style="width: 50px;">
							
						</td>
						<td style="width: 75px;">
							<?php echo ((is_array($_tmp=$this->_tpl_vars['dokter']->getStart())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%H:%M") : smarty_modifier_date_format($_tmp, "%H:%M")); ?>

						</td>
						<td style="width: 75px;">
							<?php echo ((is_array($_tmp=$this->_tpl_vars['dokter']->getStop())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%H:%M") : smarty_modifier_date_format($_tmp, "%H:%M")); ?>

						</td>
						<td>
							Dr. <?php echo $this->_tpl_vars['dokter']->getNaam(); ?>
 <?php echo $this->_tpl_vars['dokter']->getVoornaam(); ?>

						</td>
					</tr>
				<?php endforeach; endif; unset($_from); ?>
				</table>
			</td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
		<tr class="gridfoot">
			<td colspan="999">
				
			</td>
		</tr>
	</table>
</form>
</p>