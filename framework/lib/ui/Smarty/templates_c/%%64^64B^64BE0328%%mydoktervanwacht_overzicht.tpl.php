<?php /* Smarty version 2.6.18, created on 2010-09-21 08:58:40
         compiled from mydoktervanwacht_overzicht.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'mydoktervanwacht_overzicht.tpl', 20, false),array('function', 'ajaxrequest', 'mydoktervanwacht_overzicht.tpl', 23, false),array('modifier', 'date_format', 'mydoktervanwacht_overzicht.tpl', 32, false),)), $this); ?>
<h1>Dokter van wacht</h1>
<div class="headerline">&nbsp;</div>
<p>
<table class="grid">
	<tr class="gridhead">
		<th>
			Specialisme
		</th>
		<th>
			Dokter
		</th>
		<th>
			Van
		</th>
		<th>
			Tot
		</th>
	</tr>
<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['spec']):
?>
<?php echo smarty_function_cycle(array('values' => "gridrow_A,gridrow_B",'assign' => 'rowcycle'), $this);?>

	<tr class="gridrow <?php echo $this->_tpl_vars['rowcycle']; ?>
">
		<td style="padding-left: 5px;">
			<strong><a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['spec']['request']), $this);?>
"><?php echo $this->_tpl_vars['spec']['name']; ?>
</a></strong>
		</td>
		<td>
			<?php if ($this->_tpl_vars['spec']['wachtdokter'] instanceof wachtdokterviewObject): ?>
				Dr. <?php echo $this->_tpl_vars['spec']['wachtdokter']->getNaam(); ?>
 <?php echo $this->_tpl_vars['spec']['wachtdokter']->getVoornaam(); ?>

			<?php endif; ?>
		</td>
		<td>
			<?php if ($this->_tpl_vars['spec']['wachtdokter'] instanceof wachtdokterviewObject): ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['spec']['wachtdokter']->getStart())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d/%m/%Y - %H:%M:%S") : smarty_modifier_date_format($_tmp, "%d/%m/%Y - %H:%M:%S")); ?>

			<?php endif; ?>
		</td>
		<td>
			<?php if ($this->_tpl_vars['spec']['wachtdokter'] instanceof wachtdokterviewObject): ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['spec']['wachtdokter']->getStop())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d/%m/%Y - %H:%M:%S") : smarty_modifier_date_format($_tmp, "%d/%m/%Y - %H:%M:%S")); ?>

			<?php endif; ?>
		</td>
	</tr>
<?php endforeach; endif; unset($_from); ?>
	<tr class="gridfoot">
		<td>
&nbsp;
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