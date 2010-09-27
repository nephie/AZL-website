<?php /* Smarty version 2.6.18, created on 2010-04-28 11:21:25
         compiled from myform_suggestresult.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'rawurlencode', 'myform_suggestresult.tpl', 2, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['results'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['results']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['results']['iteration']++;
?>
<div id="suggestresults_<?php echo $this->_tpl_vars['id']; ?>
_<?php echo ($this->_foreach['results']['iteration']-1); ?>
" class="suggestresultitem" onMouseOver="suggest_handlemouseover('<?php echo $this->_tpl_vars['id']; ?>
', <?php echo ($this->_foreach['results']['iteration']-1); ?>
);" onClick="suggest_fillfield('<?php echo $this->_tpl_vars['id']; ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['item'])) ? $this->_run_mod_handler('rawurlencode', true, $_tmp) : rawurlencode($_tmp)); ?>
')"><?php echo $this->_tpl_vars['item']; ?>
</div>
<?php endforeach; endif; unset($_from); ?>