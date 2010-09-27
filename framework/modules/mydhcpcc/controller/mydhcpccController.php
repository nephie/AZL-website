<?php
class mydhcpccController extends controller {
	
	public function adddokter($parameters = array()){
		require (FRAMEWORK . DS . 'conf' . DS . 'mydhcpcc.php');
		$view = new ui($this);
		
		$form = new form($parameters);
			
		$form->addField(new textField('mac','Mac','',array('required','mac')));
		$form->addField(new textField('desc','Omschrijving','',array('required')));
		
		if($form->validate()){
			$output = array();
			
			$lastline = exec('c:' . DS . 'plink.exe ' . $siecarehost . ' -l ' . $siecareuser . ' -i ' . $siecareppk . ' sudo /usr/sbin/adddokter ' . $form->getFieldvalue('mac') . ' ' . $form->getFieldvalue('desc') . ' && exit',$output);
			
			if ($lastline == 'Starting dhcpd: [  OK  ]'){
				$form->clear();
			}
			
			$output = implode('<br />', $output);
			
			$this->response->assign('formerror_' . $form->getId() , 'innerHTML' , $output,true);
			
		}
		elseif(!$form->isSent()) {
			$view->assign('form', $form);
			$this->response->assign($this->self,'innerHTML',$view->fetch('mydhcpcc_adddokter.tpl'));
		}
	}
}
?>