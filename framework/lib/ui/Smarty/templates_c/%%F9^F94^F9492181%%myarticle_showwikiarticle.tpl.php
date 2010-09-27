<?php /* Smarty version 2.6.18, created on 2010-05-03 08:47:57
         compiled from myarticle_showwikiarticle.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxrequest', 'myarticle_showwikiarticle.tpl', 7, false),)), $this); ?>
<?php if ($this->_tpl_vars['article'] instanceof myarticleversionObject): ?>
<div id="article_<?php echo $this->_tpl_vars['article']->getId(); ?>
" class="article">
	<div id="wikibreadcrumbs_<?php echo $this->_tpl_vars['sectionid']; ?>
" class="breadcrumbs">
		<?php if (count ( $this->_tpl_vars['breadcrumbs'] ) > 1): ?>
			<?php $_from = $this->_tpl_vars['breadcrumbs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['crumbs'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['crumbs']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['crumb']):
        $this->_foreach['crumbs']['iteration']++;
?>
				<?php if (($this->_foreach['crumbs']['iteration']-1) >= $this->_foreach['crumbs']['total'] - 5): ?>
				<a href="#" onclick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['crumb'][0]), $this);?>
"><?php echo $this->_tpl_vars['crumb'][1]; ?>
</a>
				<?php if (! ($this->_foreach['crumbs']['iteration'] == $this->_foreach['crumbs']['total'])): ?>&gt;<?php endif; ?>
				<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>
	</div>
	<div class="wikisearch" style="position: absolute; right: 0px; top: -10px;">
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inlineform.tpl", 'smarty_include_vars' => array('form' => $this->_tpl_vars['searchform'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</div>
	<br />
	<div style="position:relative;">
	<h1><?php echo $this->_tpl_vars['article']->getTitle(); ?>
<?php if ($this->_tpl_vars['editrequest'] instanceof ajaxrequest): ?>
	<a  class="wikiedit" href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['editrequest']), $this);?>
"><img src="files/images/edit_gridrow_A.png" title="Aanpassen"/></a>
	<?php endif; ?></h1>

	<div class="headerline">&nbsp;</div>
	<?php echo $this->_tpl_vars['article']->getWikicontent($this->_tpl_vars['sectionid'],$this->_tpl_vars['self']); ?>

	</div>
</div>
<?php endif; ?>