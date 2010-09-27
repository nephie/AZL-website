<?php /* Smarty version 2.6.18, created on 2010-04-28 11:21:22
         compiled from myarticle_showsection.tpl */ ?>
<?php $_from = $this->_tpl_vars['articles']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['article']):
?>
	<?php if ($this->_tpl_vars['article'] instanceof myarticleversionObject): ?>
	<div id="article_<?php echo $this->_tpl_vars['article']->getId(); ?>
" class="article">
		<h1><?php echo $this->_tpl_vars['article']->getTitle(); ?>
</h1>
		<div class="headerline">&nbsp;</div>
		<?php echo $this->_tpl_vars['article']->getContent(); ?>

		<p>
			&nbsp;
		</p>
	</div>
	<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>