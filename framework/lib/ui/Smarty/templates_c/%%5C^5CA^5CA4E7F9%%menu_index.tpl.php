<?php /* Smarty version 2.6.18, created on 2010-04-28 11:21:23
         compiled from menu_index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'math', 'menu_index.tpl', 6, false),array('function', 'pagerequest', 'menu_index.tpl', 44, false),array('modifier', 'wordwrap', 'menu_index.tpl', 45, false),)), $this); ?>
<div class="menu">
<?php if (!function_exists('smarty_fun_menurecursion')) { function smarty_fun_menurecursion(&$smarty, $params) { $_fun_tpl_vars = $smarty->_tpl_vars; $smarty->assign($params);  ?>
	<?php $smarty->assign('i', 0); ?>
	<?php $_from = $smarty->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $smarty->_tpl_vars['page']):
?>
		<?php $smarty->assign('i', $smarty->_tpl_vars['i']+1); ?>	
		<?php echo smarty_function_math(array('assign' => 'padding','equation' => "10+x*5",'x' => $smarty->_tpl_vars['level']), $smarty);?>

		<?php if (isset ( $smarty->_tpl_vars['page']['subpages'] )): ?>
			<?php $smarty->assign('togglerlevel', "toggler_".($smarty->_tpl_vars['level'])); ?>
		<?php else: ?>
			<?php $smarty->assign('togglerlevel', ""); ?>
		<?php endif; ?>
		
		<?php if ($smarty->_tpl_vars['i'] == count ( $smarty->_tpl_vars['list'] )): ?>
			<?php if (isset ( $smarty->_tpl_vars['page']['subpages'] )): ?>
				<?php $smarty->assign('last', 'last_withsub'); ?>
				<?php $smarty->assign('tmplast', 'false'); ?>
			<?php else: ?>
				<?php $smarty->assign('last', 'last_withoutsub'); ?>
				<?php if ($smarty->_tpl_vars['rlast'] == 'true'): ?>
					<?php $smarty->assign('tmplast', 'true'); ?>
				<?php else: ?>
					<?php $smarty->assign('tmplast', 'false'); ?>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ($smarty->_tpl_vars['rlast'] == 'true'): ?>
				<?php $smarty->assign('tmplast2', 'true'); ?>
			<?php else: ?>
				<?php $smarty->assign('tmplast2', false); ?>
			<?php endif; ?>
		<?php else: ?>
			<?php $smarty->assign('last', ""); ?>
			<?php $smarty->assign('tmplast', 'false'); ?>
			<?php $smarty->assign('tmplast2', 'false'); ?>
		<?php endif; ?>

		<?php if ($smarty->_tpl_vars['tmplast'] == 'true'): ?>
			<?php $smarty->assign('last_last', 'last_last'); ?>
		<?php else: ?>
					<?php endif; ?>
		
		
		<div class="toggler <?php echo $smarty->_tpl_vars['togglerlevel']; ?>
 toggler_level_<?php echo $smarty->_tpl_vars['level']; ?>
 <?php echo $smarty->_tpl_vars['page']['status']; ?>
 <?php echo $smarty->_tpl_vars['page']['status_subpages']; ?>
 <?php echo $smarty->_tpl_vars['last']; ?>
 <?php echo $smarty->_tpl_vars['last_last']; ?>
" style="padding-left:<?php echo $smarty->_tpl_vars['padding']; ?>
px;">
			<a href="<?php echo smarty_function_pagerequest(array('request' => $smarty->_tpl_vars['page']['page']->getRequest()), $smarty);?>
">
				<?php echo ((is_array($_tmp=$smarty->_tpl_vars['page']['page']->getTitle())) ? $smarty->_run_mod_handler('wordwrap', true, $_tmp, 26, "<br />\n") : smarty_modifier_wordwrap($_tmp, 26, "<br />\n")); ?>

			</a><div class="menuline">&nbsp;</div>	
		</div>
		<?php if (isset ( $smarty->_tpl_vars['page']['subpages'] )): ?>
		<div class="content_<?php echo $smarty->_tpl_vars['level']; ?>
">
			<?php smarty_fun_menurecursion($smarty, array('list'=>$smarty->_tpl_vars['page']['subpages'],'level'=>$smarty->_tpl_vars['level']+1,'rlast'=>$smarty->_tpl_vars['tmplast2']));  ?>
		</div>
		<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
<?php  $smarty->_tpl_vars = $_fun_tpl_vars; }} smarty_fun_menurecursion($this, array('list'=>$this->_tpl_vars['menu'],'level'=>'1','rlast'=>'true'));  ?>
</div>