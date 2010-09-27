<?php /* Smarty version 2.6.18, created on 2010-05-11 15:03:10
         compiled from mystats_showbedbez.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxrequest', 'mystats_showbedbez.tpl', 3, false),array('function', 'cycle', 'mystats_showbedbez.tpl', 24, false),array('function', 'math', 'mystats_showbedbez.tpl', 25, false),)), $this); ?>
<h1>Statistieken bedbezetting</h1>
<div class="headerline">&nbsp;</div>
<a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['printrequest']), $this);?>
">Printversie</a>
<p>
<table class="grid">
	<tr class="gridhead">
		<th>
			Dienst
		</th>
		<th>
			Bezet
		</th>
		<th>
			Totaal
		</th>
		<th>
			Bezettingsgraad
		</th>
		<th>
			Grafiek
		</th>
	</tr>
<?php $_from = $this->_tpl_vars['diensten']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['diensten'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['diensten']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['dienstarr']):
        $this->_foreach['diensten']['iteration']++;
?>
<?php echo smarty_function_cycle(array('values' => "gridrow_A,gridrow_B",'assign' => 'rowcycle'), $this);?>

<?php echo smarty_function_math(array('equation' => "(bezet / totaal) * 100 ",'bezet' => $this->_tpl_vars['dienstarr']['count'],'totaal' => $this->_tpl_vars['dienstarr']['dienst']->getAantalbedden(),'format' => "%.0f",'assign' => 'procent'), $this);?>

<?php if ($this->_tpl_vars['procent'] < 80): ?>
 	<?php $this->assign('procentcolor1', ""); ?>
 	<?php $this->assign('procentcolor2', ""); ?>
 	<?php $this->assign('procentcolor3', ""); ?>
 <?php elseif ($this->_tpl_vars['procent'] < 90): ?>
 	<?php $this->assign('procentcolor1', "border-top: 1px solid #9F6000;border-bottom: 1px solid #9F6000; background-color: #DCCD91;"); ?>
 	<?php $this->assign('procentcolor2', "border-left: 1px solid #9F6000; background-color: #DCCD91;"); ?>
 	<?php $this->assign('procentcolor3', "border-right: 1px solid #9F6000; background-color: #DCCD91;"); ?>
 <?php else: ?>
	<?php $this->assign('procentcolor1', "border-top: 1px solid #D8000C;border-bottom: 1px solid #D8000C; background-color: #DD8787;"); ?>
 	<?php $this->assign('procentcolor2', "border-left: 1px solid #D8000C; background-color: #DD8787;"); ?>
 	<?php $this->assign('procentcolor3', "border-right: 1px solid #D8000C; background-color: #DD8787;"); ?>
 <?php endif; ?>
	<tr class="gridrow <?php echo $this->_tpl_vars['rowcycle']; ?>
">
		<td style="padding-left: 5px; <?php echo $this->_tpl_vars['procentcolor1']; ?>
 <?php echo $this->_tpl_vars['procentcolor2']; ?>
">
			<?php echo $this->_tpl_vars['dienstarr']['dienst']->getName(); ?>

		</td>
		<td style="<?php echo $this->_tpl_vars['procentcolor1']; ?>
">
		 	<?php echo $this->_tpl_vars['dienstarr']['count']; ?>

		 </td>
		 <td style="<?php echo $this->_tpl_vars['procentcolor1']; ?>
">
		 	<?php echo $this->_tpl_vars['dienstarr']['dienst']->getAantalbedden(); ?>

		 </td>
		 <td style="padding: 3px; <?php echo $this->_tpl_vars['procentcolor1']; ?>
">



		 	<span <?php echo $this->_tpl_vars['procentcolor']; ?>
>
		 		<?php echo $this->_tpl_vars['procent']; ?>
%
		 	</span>
		 </td>
		 <td style="<?php echo $this->_tpl_vars['procentcolor1']; ?>
 <?php echo $this->_tpl_vars['procentcolor3']; ?>
">
		 	<a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['dienstarr']['graphtoday']), $this);?>
" >Vandaag</a> |
		 	<a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['dienstarr']['graphyesterday']), $this);?>
" >Gisteren</a> |
		 	<a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['dienstarr']['graphtm']), $this);?>
" >Deze maand</a> |
		 	<a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['dienstarr']['graphlm']), $this);?>
" >vorige maand</a> |
		 	<a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['dienstarr']['graphty']), $this);?>
" >Dit jaar</a> |
		 	<a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['dienstarr']['graphly']), $this);?>
" >Vorig jaar</a>
		 </td>
	</tr>
<?php endforeach; endif; unset($_from); ?>
	<tr class="gridfoot">
		<td>
			Totaal
		</td>
		<td>
			<?php echo $this->_tpl_vars['total']; ?>

		</td>
		<td>
			<?php echo $this->_tpl_vars['totalmax']; ?>

		</td>
		<td>
			<?php echo smarty_function_math(array('equation' => "(bezet / totaal) * 100 ",'bezet' => $this->_tpl_vars['total'],'totaal' => $this->_tpl_vars['totalmax'],'format' => "%.0f",'assign' => 'procent'), $this);?>



		 		<?php echo $this->_tpl_vars['procent']; ?>
%
		</td>
		<td style="padding-left: 0px;">
			<a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['totalgraph']['graphtoday']), $this);?>
" >Vandaag</a> |
		 	<a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['totalgraph']['graphyesterday']), $this);?>
" >Gisteren</a> |
		 	<a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['totalgraph']['graphtm']), $this);?>
" >Deze maand</a> |
		 	<a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['totalgraph']['graphlm']), $this);?>
" >vorige maand</a> |
		 	<a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['totalgraph']['graphty']), $this);?>
" >Dit jaar</a> |
		 	<a href="#" onClick="<?php echo smarty_function_ajaxrequest(array('request' => $this->_tpl_vars['totalgraph']['graphly']), $this);?>
" >Vorig jaar</a>
		</td>
	</tr>
</table>
</p>
<p>
	<div id="bedbezgraph"></div>
</p>