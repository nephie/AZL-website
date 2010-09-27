<?php /* Smarty version 2.6.18, created on 2010-05-04 09:29:30
         compiled from medibridgeparser_showlog.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxrequest', 'medibridgeparser_showlog.tpl', 3, false),array('modifier', 'date_format', 'medibridgeparser_showlog.tpl', 40, false),)), $this); ?>
<h1>Log voor bericht: <?php echo $this->_tpl_vars['log']->getFilename(); ?>
</h1>
<div class="headerline">&nbsp;</div>
<div style="position: absolute; right: 0px; top: 0px;"><a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['closerequest']), $this);?>
">Sluiten</a></div>

<table>
	<tr>
		<td>
			<strong>Status:</strong>
		</td>
		<td>
			<?php echo $this->_tpl_vars['log']->getStatusdelivery(); ?>
<?php if ($this->_tpl_vars['log']->getMessagedelivery() != ''): ?>: <?php echo $this->_tpl_vars['log']->getMessagedelivery(); ?>
<?php endif; ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<strong>Verzender:</strong>
		</td>
		<td>
			<?php echo $this->_tpl_vars['log']->getSender(); ?>
 <?php if ($this->_tpl_vars['plog']->getSender() != $this->_tpl_vars['log']->getSender()): ?>(<?php echo $this->_tpl_vars['plog']->getSender(); ?>
)<?php endif; ?>
		</td>
	</tr>
	<tr>
		<td>
			<strong>Ontvanger:</strong>
		</td>
		<td>
			<?php echo $this->_tpl_vars['log']->getReciever(); ?>
 <?php if ($this->_tpl_vars['plog']->getReciever() != $this->_tpl_vars['log']->getReciever()): ?>(<?php echo $this->_tpl_vars['plog']->getReciever(); ?>
)<?php endif; ?>
		</td>
	</tr>
	<tr>
		<td>
			<strong>Verwerkingsdatum:</strong>
		</td>
		<td>
			<?php echo ((is_array($_tmp=$this->_tpl_vars['log']->getParsedate())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%H:%M:%S - %d/%m/%Y") : smarty_modifier_date_format($_tmp, "%H:%M:%S - %d/%m/%Y")); ?>

		</td>
	</tr>
	<tr>
		<td colspan="2">
			&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<strong>Bronbestand: </strong>
		</td>
		<td>
			<?php echo $this->_tpl_vars['basesourcemap']; ?>
\<strong><?php echo $this->_tpl_vars['log']->getRelativesourcepath(); ?>
</strong>
		</td>
	</tr>
	<?php if ($this->_tpl_vars['log']->getStatusdelivery() == 'DELIVERY_SUCCESS' || $this->_tpl_vars['log']->getStatusdelivery() == 'DELIVERY_ERROR'): ?>
	<tr>
		<td>
			<strong>Doelbestand: </strong>
		</td>
		<td>
			<?php echo $this->_tpl_vars['basedestinationmap']; ?>
\<strong><?php echo $this->_tpl_vars['log']->getRelativedestinationpath(); ?>
\<?php echo $this->_tpl_vars['log']->getFilename(); ?>
</strong>
		</td>
	</tr>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['log']->getStatusdelivery() == 'DELIVERY_ERROR' || $this->_tpl_vars['log']->getStatusdelivery() == 'PARSER_ERROR' || $this->_tpl_vars['log']->getStatusdelivery() == 'NO_PARSER' || $this->_tpl_vars['log']->getStatusdelivery() == 'MESSAGE_IGNORED'): ?>
	<tr>
		<td>
			<strong>Bericht verplaatst naar: </strong>
		</td>
		<td>
			<?php echo $this->_tpl_vars['errormap']; ?>
\<strong><?php echo $this->_tpl_vars['log']->getRelativeerrorpath(); ?>
</strong>
		</td>
	</tr>
	<?php endif; ?>
	<tr>
		<td colspan="2">
			&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<strong>Status backup:</strong>
		</td>
		<td>
			<?php echo $this->_tpl_vars['log']->getStatusbackup(); ?>
<?php if ($this->_tpl_vars['log']->getMessagebackup() != ''): ?>: <?php echo $this->_tpl_vars['log']->getMessagebackup(); ?>
<?php endif; ?>
		</td>
	</tr>
	<?php if ($this->_tpl_vars['log']->getStatusbackup() != 'NO_BACKUP_REQUESTED'): ?>
	<tr>
		<td>
			<strong>Backup: </strong>
		</td>
		<td>
			<?php echo $this->_tpl_vars['backupmap']; ?>
\<strong><?php echo $this->_tpl_vars['log']->getRelativebackuppath(); ?>
</strong>
		</td>
	</tr>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['log']->getStatuserror() == 'ERROR_MOVE_ERROR'): ?>
	<tr>
		<td colspan="2">
			Het bericht kon niet naar de foutmap verplaatst worden! Foutmelding: <?php echo $this->_tpl_vars['log']->getMessageerror(); ?>

		</td>
	</tr>
	<?php endif; ?>
</table>
<p>
	<input id="editlink" type="button" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['editrequest']), $this);?>
" value="Bewerk dit bericht" />
</p>
<p>&nbsp;</p>