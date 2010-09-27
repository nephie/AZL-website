<?php /* Smarty version 2.6.18, created on 2010-04-30 11:55:45
         compiled from myacl_deleteacl.tpl */ ?>
<p>
	Bent u zeker dat u dit wilt verwijderen?
</p>
<p>
	<table>
		<tr>
			<td>
				<strong>Aanvrager: </strong>
			</td>
			<td>
				<?php echo $this->_tpl_vars['acl']->getRequester(); ?>

			</td>
		</tr>
		<tr>
			<td>
				<strong>Item: </strong>
			</td>
			<td>
				<?php echo $this->_tpl_vars['acl']->getObject(); ?>

			</td>
		</tr>
		<tr>
			<td>
				<strong>Recht: </strong>
			</td>
			<td>
				<?php echo $this->_tpl_vars['acl']->getRightdesc(); ?>

			</td>
		</tr>
	</table>
</p>
<br />
<?php if (count ( $this->_tpl_vars['dependant'] ) > 0): ?>
<p>
	Dit recht verwijderen zal voor deze aanvrager ook de volgende rechten verwijderen:
	<ul>
		<?php $_from = $this->_tpl_vars['dependant']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['depacl']):
?>
			<li><?php echo $this->_tpl_vars['depacl']->getRightdesc(); ?>
</li>
		<?php endforeach; endif; unset($_from); ?>
	</ul>
</p>
<br />
<?php endif; ?>