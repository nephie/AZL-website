<?php /* Smarty version 2.6.18, created on 2010-04-28 12:37:41
         compiled from myticket_listmytickets.tpl */ ?>
<div id="ticketcontainer" class="extracontainer" style="position: relative;"></div>
<?php if (is_array ( $this->_tpl_vars['tickets'] )): ?>
	<?php $_from = $this->_tpl_vars['tickets']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['dienst'] => $this->_tpl_vars['ticketlist']):
?>
		<h1>Meldingen van dienst <?php echo $this->_tpl_vars['dienst']; ?>
</h1>
		<div class="headerline">&nbsp;</div>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygrid.tpl", 'smarty_include_vars' => array('grid' => $this->_tpl_vars['ticketlist'],'columns' => "array('Titel' => 'titel', 'Aan' => 'toname', 'Contact' => 'contact', 'Status' => 'status', 'Gemeld op' => array('column' => 'time', 'modifier' => 'date_format:\"%d/%m/%Y - %H:%M\"'), 'volgnummer' => 'id')")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endforeach; endif; unset($_from); ?>
<?php endif; ?>