<?php /* Smarty version 2.6.18, created on 2010-05-04 16:30:18
         compiled from keukenpakket_showstatus.tpl */ ?>
<h1>Keukenpakket</h1>
<div class="headerline">&nbsp;</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form.tpl", 'smarty_include_vars' => array('form' => $this->_tpl_vars['form'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<strong>Aantal patiënten: <?php echo $this->_tpl_vars['count']; ?>
</strong>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mygrid.tpl", 'smarty_include_vars' => array('grid' => $this->_tpl_vars['grid'],'columns' => "array('Kamer' => 'kamer', 'Bed' => 'bed', 'Voornaam' => 'voornaam', 'Achternaam' => 'achternaam')")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>