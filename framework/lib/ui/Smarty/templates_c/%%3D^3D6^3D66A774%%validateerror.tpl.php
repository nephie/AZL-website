<?php /* Smarty version 2.6.18, created on 2010-04-28 11:21:54
         compiled from validateerror.tpl */ ?>
<?php if (is_array ( $this->_tpl_vars['validateerror'] )): ?><?php $_from = $this->_tpl_vars['validateerror']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['validateError'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['validateError']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['error']):
        $this->_foreach['validateError']['iteration']++;
?><?php echo $this->_tpl_vars['error']; ?>
<?php if (! ($this->_foreach['validateError']['iteration'] == $this->_foreach['validateError']['total'])): ?>, <?php endif; ?><?php endforeach; endif; unset($_from); ?><?php endif; ?>