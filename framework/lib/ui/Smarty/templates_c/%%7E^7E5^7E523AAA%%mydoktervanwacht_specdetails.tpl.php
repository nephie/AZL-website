<?php /* Smarty version 2.6.18, created on 2010-09-20 12:10:38
         compiled from mydoktervanwacht_specdetails.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxrequest', 'mydoktervanwacht_specdetails.tpl', 3, false),array('function', 'cycle', 'mydoktervanwacht_specdetails.tpl', 39, false),array('modifier', 'date_format', 'mydoktervanwacht_specdetails.tpl', 47, false),)), $this); ?>
<div style="position: relative">
<h1>Dokter van wacht: <?php echo $this->_tpl_vars['specialisme']->getName(); ?>
</h1>
<div style="position: absolute; right: 0px; top: 0px;"><a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['closerequest']), $this);?>
">Terug naar overzicht</a></div>
<div class="headerline">&nbsp;</div>
</div>
<p>
	<div id="specgrid_<?php echo $this->_tpl_vars['specialisme']->getId(); ?>
"></div>
	<table class="grid">
		<tr class="gridhead">
			<td style="vertical-align: middle; width: 10px; text-align: left; position: relative;" >
					
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
				<?php if ($this->_tpl_vars['day']['addrequest'] instanceof ajaxrequest): ?>
						<a onclick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['day']['addrequest']), $this);?>
" href="#">
							<?php echo ((is_array($_tmp=$this->_tpl_vars['day']['start'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d/%m/%Y") : smarty_modifier_date_format($_tmp, "%d/%m/%Y")); ?>
					
						</a>
				<?php else: ?>
					<?php echo ((is_array($_tmp=$this->_tpl_vars['day']['start'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d/%m/%Y") : smarty_modifier_date_format($_tmp, "%d/%m/%Y")); ?>

				<?php endif; ?>
				
			</td>
			<td>
				<table>
				<?php $_from = $this->_tpl_vars['day']['dokters']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['dokter']):
?>
					<tr>
						<td style="width: 50px;">
							<?php if ($this->_tpl_vars['dokter']['request'] instanceof ajaxrequest): ?>
								<a onclick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['dokter']['request']), $this);?>
" href="#">
								<span><img id="delbutton" title="Verwijderen" src="files/images/delete.png"></span>
							<?php endif; ?>
						</td>
						<td style="width: 75px;">
							<?php if ($this->_tpl_vars['dokter']['dokter']->getStart() < $this->_tpl_vars['day']['start']): ?>
								00:00
							<?php else: ?>							
								<?php echo ((is_array($_tmp=$this->_tpl_vars['dokter']['dokter']->getStart())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%H:%M") : smarty_modifier_date_format($_tmp, "%H:%M")); ?>

							<?php endif; ?>
						</td>
						<td style="width: 75px;">
							<?php if ($this->_tpl_vars['dokter']['dokter']->getStop() > $this->_tpl_vars['day']['start'] + 86400 -1): ?>
								23:59
							<?php else: ?>
								<?php echo ((is_array($_tmp=$this->_tpl_vars['dokter']['dokter']->getStop())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%H:%M") : smarty_modifier_date_format($_tmp, "%H:%M")); ?>

							<?php endif; ?>
						</td>
						<td>
							Dr. <?php echo $this->_tpl_vars['dokter']['dokter']->getNaam(); ?>
 <?php echo $this->_tpl_vars['dokter']['dokter']->getVoornaam(); ?>

						</td>
					</tr>
				<?php endforeach; endif; unset($_from); ?>
				</table>
			</td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
		<tr class="gridfoot">
			<td colspan="999">
				<span><a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['prevrequest']), $this);?>
">Vorige maand</a></span>
			
				<span style="float: right;"><a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['nextrequest']), $this);?>
">Volgende maand</a></span>
			</td>
		</tr>
	</table>
</p>
<p>
<div id="acllist_wachtdokter_<?php echo $this->_tpl_vars['specialisme']->getId(); ?>
"></div>	
</p>