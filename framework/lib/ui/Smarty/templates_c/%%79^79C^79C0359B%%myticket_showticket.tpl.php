<?php /* Smarty version 2.6.18, created on 2010-04-28 13:28:11
         compiled from myticket_showticket.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxrequest', 'myticket_showticket.tpl', 3, false),array('modifier', 'date_format', 'myticket_showticket.tpl', 17, false),array('modifier', 'nl2br', 'myticket_showticket.tpl', 32, false),)), $this); ?>
<h1>Melding [<?php echo $this->_tpl_vars['ticket']->getId(); ?>
] aan [<?php echo $this->_tpl_vars['ticket']->getToname(); ?>
]: <?php echo $this->_tpl_vars['ticket']->getTitel(); ?>
</h1>
<div class="headerline">&nbsp;</div>
<div style="position: absolute; right: 0px; top: 0px;"><a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['closerequest']), $this);?>
">Sluiten</a></div>
<table>
	<tr>
		<td valign="top"><strong>Contact:</strong></td>
		<td><?php echo $this->_tpl_vars['ticket']->getContact(); ?>
</td>
	</tr>

	<tr>
		<td valign="top"><strong>Afdeling:</strong></td>
		<td><?php echo $this->_tpl_vars['ticket']->getDienst(); ?>
</td>
	</tr>

	<tr>
		<td valign="top"><strong>Gemeld op:</strong></td>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['ticket']->getTime())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d/%m/%Y - %H:%M") : smarty_modifier_date_format($_tmp, "%d/%m/%Y - %H:%M")); ?>
</td>
	</tr>

	<tr>
		<td valign="top"><strong>Status:</strong></td>
		<td><?php echo $this->_tpl_vars['ticket']->getStatus(); ?>
</td>
	</tr>

	<tr>
		<td valign="top"><strong>Gemeld door:</strong></td>
		<td><?php echo $this->_tpl_vars['ticket']->getUser(); ?>
</td>
	</tr>

	<tr>
		<td valign="top"><strong>Melding:</strong></td>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['ticket']->getMessage())) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
	</tr>
</table>
<br />
<br />