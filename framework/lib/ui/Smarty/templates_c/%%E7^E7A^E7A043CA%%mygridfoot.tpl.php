<?php /* Smarty version 2.6.18, created on 2010-04-28 11:21:54
         compiled from mygridfoot.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxrequest', 'mygridfoot.tpl', 7, false),)), $this); ?>
<tr class="gridfoot">
	<td colspan="999" style="padding: 0px;">
		<table class="gridfoottable">
			<tr>
				<td id="prevpage">
					<?php if ($this->_tpl_vars['grid']->getTotalpages() > 1 && $this->_tpl_vars['grid']->getPage() != 1): ?>
						<a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['grid']->getGotofirstpagerequest()), $this);?>
" >First</a> &nbsp;|&nbsp; <a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['grid']->getGotopreviouspagerequest()), $this);?>
" >Previous</a>
					<?php endif; ?>&nbsp;
				</td>
				<td id="jumppage">
					<?php if ($this->_tpl_vars['grid']->getTotalpages() > 1): ?>
						Page <strong><?php echo $this->_tpl_vars['grid']->getPage(); ?>
</strong> of <strong><?php echo $this->_tpl_vars['grid']->getTotalpages(); ?>
</strong> &nbsp;&nbsp;&nbsp;
						<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inlineform.tpl", 'smarty_include_vars' => array('form' => $this->_tpl_vars['grid']->getGotopageform())));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
					<?php endif; ?>
				</td>
				<td id="search">
					<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inlineform.tpl", 'smarty_include_vars' => array('form' => $this->_tpl_vars['grid']->getSearchform())));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php if ($this->_tpl_vars['grid']->getConditions() != $this->_tpl_vars['grid']->getDefaultconditions()): ?> <a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['grid']->getClearsearchrequest()), $this);?>
">Clear search</a><?php endif; ?>
				</td>
				<td id="nextpage">
					<?php if ($this->_tpl_vars['grid']->getTotalpages() > 1 && $this->_tpl_vars['grid']->getPage() != $this->_tpl_vars['grid']->getTotalpages()): ?>
						<a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['grid']->getGotonextpagerequest()), $this);?>
" >Next</a> &nbsp;|&nbsp; <a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['grid']->getGotolastpagerequest()), $this);?>
" >Last</a>
					<?php endif; ?>&nbsp;
				</td>
			</tr>
		</table>
	</td>
</tr>