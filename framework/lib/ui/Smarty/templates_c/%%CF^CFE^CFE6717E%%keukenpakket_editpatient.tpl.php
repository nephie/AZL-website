<?php /* Smarty version 2.6.18, created on 2010-05-04 16:30:51
         compiled from keukenpakket_editpatient.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'keukenpakket_editpatient.tpl', 32, false),)), $this); ?>
<p>
<table>
	<tr>
		<td>
			<strong>Naam: </strong>&nbsp;
		</td>
		<td>
			<?php echo $this->_tpl_vars['patient']->getVoornaam(); ?>
 <?php echo $this->_tpl_vars['patient']->getAchternaam(); ?>

		</td>
	</tr>
	<tr>
		<td>
			<strong>Patiëntnummer: </strong>&nbsp;
		</td>
		<td>
			<?php echo $this->_tpl_vars['patient']->getPatientnr(); ?>

		</td>
	</tr>
	<tr>
		<td>
			<strong>Huidig dossiernummer: </strong>&nbsp;
		</td>
		<td>
			<?php echo $this->_tpl_vars['patient']->getCurrentdossiernr(); ?>

		</td>
	</tr>
	<tr>
		<td>
			<strong>Geboortedatum: </strong>&nbsp;
		</td>
		<td>
			<?php echo ((is_array($_tmp=$this->_tpl_vars['patient']->getGeboortedatum())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d/%m/%Y") : smarty_modifier_date_format($_tmp, "%d/%m/%Y")); ?>

		</td>
	</tr>
	<tr>
		<td>
			<strong>Geslacht: </strong>&nbsp;
		</td>
		<td>
			<?php echo $this->_tpl_vars['patient']->getGeslacht(); ?>

		</td>
	</tr>
	<tr>
		<td>
			&nbsp;
		</td>
		<td>
			&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<strong>Kamer: </strong>&nbsp;
		</td>
		<td>
			<?php echo $this->_tpl_vars['patient']->getKamer(); ?>

		</td>
	</tr>
	<tr>
		<td>
			<strong>Bed: </strong>&nbsp;
		</td>
		<td>
			<?php echo $this->_tpl_vars['patient']->getBed(); ?>

		</td>
	</tr>

</table>
</p>
<p>
	<div id="keukenpakket_form">
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "keukenpakket_form.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</div>
</p>