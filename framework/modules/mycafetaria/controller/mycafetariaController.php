<?php

class mycafetariaController extends controller {

	protected $price = 0;
	protected $order;
	protected $showpromo;

	public function hotmealperday($parameters = array()){
		$view = new ui($this);

		$start = strtotime('today');
		$stop = strtotime('tomorrow');

		$grid = new mygrid('hotmealperday');

		$grid->setModel(new processedmealorderModel());
		$grid->setDefaultpagesize(9999);

		$cond = array('AND' => array(
											'mealtype' => array('mode' => '<>','value' => 'Koude maaltijd'),
											'uur' => array('mode' => 'BETWEEN', 'value' => $start, 'topvalue' => $stop)
										)
							);

		$grid->setDefaultconditions($cond);
		$grid->setDefaultorder(array('fields' => array('user'), 'type' => 'ASC'));


		$view->assign('grid',$grid);

		$this->response->assign($this->self,'innerHTML', $view->fetch('mycafetaria_hotmealperday.tpl'));
	}

	public function statsperday($parameters = array()){
		$view = new ui($this);

		$start = strtotime('today');
		$stop = strtotime('tomorrow');

		$form = new form($parameters);

		$form->addField(new datepickerField('start','Van',false,$start,array('required')));
		$form->addField(new datepickerField('stop','Tot (exclusief)',false,$stop,array('required')));

		if($form->validate()){
			$start = $form->getFieldvalue('start');
			$stop = $form->getFieldvalue('stop');
		}

		$view->assign('form', $form);

		$view->assign('start',$start);
		$view->assign('stop',$stop);

		$mealmodel = new mealModel();
		$mealordermodel = new mealorderModel();

		$meals = $mealmodel->get();

		foreach($meals as $meal){
			$cond = array('AND' => array(
											'meal' => array('mode' => '=','value' => $meal->getName()),
											'uur' => array('mode' => 'BETWEEN', 'value' => $start, 'topvalue' => $stop)
										)
							);


			$count[$meal->getName()]['count'] =  $mealordermodel->getcount($cond);
			$tmp = new ajaxrequest('mycafetaria','showmealsperday',array('meal' => $meal->getName(),'start' => $start, 'stop' => $stop, 'target' => 'grid_mealsperday'));
			$count[$meal->getName()]['request'] = $tmp;
		}

		$view->assign('count', $count);

		$this->response->assign($this->self,'innerHTML', $view->fetch('mycafetaria_statsperday.tpl'));
	}

	public function showmealsperday($parameters = array()){
		$view = new ui($this);

		$grid = new mygrid($parameters['meal']);

		$grid->setModel(new processedmealorderModel());
		$grid->setDefaultpagesize(15);

		$cond = array('AND' => array(
											'meal' => array('mode' => '=','value' => $parameters['meal']),
											'uur' => array('mode' => 'BETWEEN', 'value' => $parameters['start'], 'topvalue' => $parameters['stop'])
										)
							);

		$grid->setDefaultconditions($cond);
		$grid->setDefaultorder(array('fields' => array('uur'), 'type' => 'DESC'));

		$grid->registerRequest('printed' , 'mycafetaria' , 'reprint' , array('id' => '{id}'));

		$view->assign('grid',$grid);
		$view->assign('meal',$parameters['meal']);

		$this->response->assign($parameters['target'],'innerHTML', $view->fetch('mycafetaria_mealsperday.tpl'));
	}

	public function showmenu($parameters = array()){
		$view = new ui($this);

		$this->response->assign($this->self,'innerHTML', $view->fetch('mycafetaria_showmenu.tpl'));
	}

	public function updateorderform($parameters = array()){
		$form = $this->buildform($parameters);

		$view = new ui($this);
		$view->assign('form' , $form);
		$view->assign('price', $this->price);
		$this->response->assign($this->self,'innerHTML', $view->fetch('mycafetaria_order.tpl'));
		if($this->showpromo){
			$this->displaypromo();
		}
		else {
			$this->hidepromo();
		}
	}

	public function listusers($value){

		require(FRAMEWORK . DS . 'conf' . DS . 'mycafetaria.php');

		$model = new userModel();
		$adresult = $model->searchnamesforgroup('*' . $value . '*', $ordergroupdn);
		foreach ($adresult as $user){
				$result[$user->getName()] = $user->getName();
		}

		//ocmw
		$adocresult = $model->searchnamesforgroup('*' . $value . '*',$ordergroupdnoc);

		foreach ($adocresult as $user){
				$result[$user->getName()] = $user->getName();
		}

		$ordermodel = new mealorderModel();
		$orders = $ordermodel->getfromUser('*' . $value . '*','',100);
		foreach ($orders as $order){
			$result[$order->getUser()] = $order->getUser();
		}

		sort($result);

		return $result;
	}

	private function buildform($parameters){

		require(FRAMEWORK . DS . 'conf' . DS . 'mycafetaria.php');

		if(isset($parameters['__field__'])){
			$changedfield = $parameters['__field__'];
		}

		$form = new form($parameters,'mycafetaria','order');
		$form->setPhasedrequest(new ajaxrequest('mycafetaria','updateorderform'));
		$form->setSubmittext('Bestel');

		$currentuser = myauth::getCurrentuser();
		$currentname = '';
		$groups = $currentuser->getGroupid();

		if(!isset($groups[$algemenegebruikers])){
			$currentname = $currentuser->getName();
		}

		if(!isset($groups[$externgroup])){
			$pricefield = 'price';
		}
		else {
			$pricefield = 'price2';
		}

		$form->addField(new suggesttextField('mycafetaria', 'listusers', 'orderuser', 'Voor wie',$currentname, array('required')));
		if($currentname != '' || $form->getFieldvalue('orderuser') != ''){
			$form->setFocusfield('mealtype');
		}

		$mealtypemodel = new mealtypeModel();
		$mealModel = new mealModel();
		$optionsetModel = new mealoptionsetModel();
		$optionModel = new mealoptionModel();

		$mealtypes = $mealtypemodel->get();

		$typeSelect = new selectField('mealtype','Soort maaltijd',array('required'));

		$typeSelect->addOption(new selectoptionField('',''));
		foreach($mealtypes as $mealtype){
			$typeSelect->addOption(new selectoptionField($mealtype->getName(),$mealtype->getId()));
		}
		$form->addField($typeSelect);

		if($form->getFieldvalue('mealtype') != ''){

			$form->setFocusfield($changedfield);

			$this->order['mealtypeid'] = $form->getFieldvalue('mealtype');

			$meals = $mealModel->getfromMealtypeid($form->getFieldvalue('mealtype'),array('fields' => array('place'),'type' => 'ASC'));

			$mealselect = new selectField('meal', 'Maaltijd', array('required'));
			$mealselect->addOption(new selectoptionField('',''));

			foreach($meals as $meal){
				$label = $meal->getName();
				if($meal->_get($pricefield) > 0){
					$label .= ' (+ €' . $meal->_get($pricefield) . ')';
				}
				$mealselect->addOption(new selectoptionField($label,$meal->getId()));
			}
			$form->addField($mealselect);
		}

		if($changedfield == 'mealtype'){
			return $form;
		}

		if($form->getFieldvalue('meal') != ''){

			$meal = $mealModel->getfromId($form->getFieldvalue('meal'));

			$this->order['mealid'] = $form->getFieldvalue('meal');

			if(count($meal) > 0)
			{

				$object = $meal[0];

				$this->price += $object->_get($pricefield);
				if(count($object->getOptionsetid()) > 0)
				{
					$optionsetconditions = array(
						'id' => array(
									'mode' => 'IN',
									'value' => $object->getOptionsetid()
								)
					);
					$optionsets = $optionsetModel->get($optionsetconditions,array('fields' => array('place'),'type' => 'ASC'));

					$ready = true;
					foreach($optionsets as $optionset){
						$tmp = $this->resolveOptionset($form,$optionset);
						if(!$tmp){
							$ready = false;
						}
					}

					if($ready){
						$form->setReady(true);
					}
				}
				else {
					$form->setReady(true);
				}

				$uurselect = new selectField('uur', 'Uur', array('required'));
				$uurselect->addOption(new selectoptionField('',''));

				$today = strtotime('today');

				$dayofweek = date('w',$today);
				if($dayofweek == 0 || $dayofweek == 6){
					$daytype = 2;
				}
				else {
					$times = array_flip($holidays);
					if(isset($times[$today])){
						$daytype = 2;
					}
					else {
						$daytype = 1;
					}
				}



				$cond = array( 'AND' => array(
												array( 'id' => array('mode' => 'IN', 'value' => $object->getBlackoutid() )),
												array('AND' => array( 'OR' => array(
																				array( 'days' => array( 'mode' => '=', 'value' => 3 )),
																				array( 'days' => array( 'mode' => '=', 'value' => $daytype ))
																				)
																	)
													)
												)
								);

				$blackoutmodel = new mealblackoutModel();
				$blackouts = $blackoutmodel->get($cond);

				// Van 'nu + 30 mins afgerond tot 30 mins' tot 24u per halfuur
				$nu = time() - $today;
				if($nu < 0) {
					$nu = 0;
				}
				for($i = 0; $i < (60*60*24); ($i += (60*30))){
					$valid = true;

					if($i <= $nu + (60*30)){
						$valid = false;
					}

					foreach ($blackouts as $blackout){
						if($i >= $blackout->getBlackoutperiodstart() && $i <= $blackout->getBlackoutperiodend() && $nu > $blackout->getTriggertime()){
							$valid = false;
							break;
						}
					}

					if($valid){
						$uur = $today + $i;
						$uurselect->addOption(new selectoptionField(date('H:i',$uur),$uur));
					}
				}

				$form->addField($uurselect);

				if($form->getFieldvalue('uur') == ''){
					$form->setReady(false);
				}
				else {
					$this->order['uur'] = $form->getFieldvalue('uur');
				}
			}

		}
		if($changedfield == 'meal'){
			return $form;
		}

		return $form;
	}

	private function resolveOptionset($form,$optionset) {
		$ready = true;
		$optionsetModel = new mealoptionsetModel();
		$optionModel = new mealoptionModel();
		$optionsetoptionModel = new mealoptionsetoptionModel();

		require(FRAMEWORK . DS . 'conf' . DS . 'mycafetaria.php');

		if(isset($parameters['__field__'])){
			$changedfield = $parameters['__field__'];
		}

		if(!isset($groups[$externgroup])){
			$pricefield = 'price';
		}
		else {
			$pricefield = 'price2';
		}

		if($optionset->getOptionsettypeid() == 1){
			$optionselect = new selectField($optionset->getId(),$optionset->getName(),array('required'));
			$optionids = $optionset->getOptionid();
			$enabled = $optionset->getEnabled();

			$conditional = false;
			if($optionset->isConditionaldefault()){
				$conddefmodel = new mealconditionaldefaultModel();

				$conddefobjects = $conddefmodel->getfromOptionset($optionset->getId());
				if(count($conddefobjects) > 0){
					foreach($conddefobjects as $conddefobject){

						if($form->getFieldvalue($conddefobject->getReferoptionset()) == $conddefobject->getReferoption()){
							$condopt = $conddefobject->getDefaultoption();

							$conditional = true;
							break;
						}

					}
				}
			}

			if(count($optionids) > 0){
				$optionconditions = array(
					'id' => array(
								'mode' => 'IN',
								'value' => $optionids
							)
				);

				$options = $optionModel->get($optionconditions,array('fields' => array('place'),'type' => 'ASC'));

				$optionselect->addOption(new selectoptionField('',''));
				foreach($options as $option){
					if($option->getId() == 60){

						$promo = false;

						$promomodel = new cafetariapromotieModel();
						$promoidcond = array('id' => array('mode' => '=', 'value' => 1));
						$promostartcond = array('starttime' => array('mode' => '<=','value' => time()));
						$promostopcond = array('stoptime' => array('mode' => '>=','value' => time()));
						$promocond = array('AND' => array($promoidcond,$promostartcond,$promostopcond));

						if(count($promomodel->get($promocond)) > 0){
							$promo = true;
						}

						if($promo){
							$label = $option->getName();
							if($option->_get($pricefield) > 0){
								$label .= ' (+ €' . $option->_get($pricefield) . ')';
							}

							if($conditional){
								if($condopt == $option->getId()){
									$optionselect->addOption(new selectoptionField($label,$option->getId(),true));
									if($form->getFieldvalue($optionset->getId()) == ''){
										$form->setFieldvalue($optionset->getId(),$condopt);
									}
								}
								else {
									$optionselect->addOption(new selectoptionField($label,$option->getId(),false));
								}
							}
							else {
								$optionselect->addOption(new selectoptionField($label,$option->getId(),$enabled[$option->getId()]));
							}

							if($form->getFieldvalue($optionset->getId()) == $option->getId()){
								$this->showpromo = true;
							}
						}
						else {
							$this->showpromo = false;
						}
					}
					else {


						$label = $option->getName();
						if($option->_get($pricefield) > 0){
							$label .= ' (+ €' . $option->_get($pricefield) . ')';
						}

						if($conditional){
							if($condopt == $option->getId()){
								$optionselect->addOption(new selectoptionField($label,$option->getId(),true));
								if($form->getFieldvalue($optionset->getId()) == ''){
									$form->setFieldvalue($optionset->getId(),$condopt);
								}
							}
							else {
								$optionselect->addOption(new selectoptionField($label,$option->getId(),false));
							}
						}
						else {
							$optionselect->addOption(new selectoptionField($label,$option->getId(),$enabled[$option->getId()]));
						}
						if($optionset->getId() == 19 && $form->getFieldvalue($optionset->getId()) == $option->getId()){
							$this->showpromo = false;
						}
					}

				}
				$form->addField($optionselect);

				if($form->getFieldvalue($optionset->getId()) == ''){
					$ready = false;
				}
			}
		}
		elseif($optionset->getOptionsettypeid() == 2){
			$options = $optionsetoptionModel->getfromOptionsetid($optionset->getId());



			if(count($options) > 0){
				foreach($options as $opt){
					$ropt = $optionModel->getfromId($opt->getOptionid());
					if(count($ropt) > 0){
						$ropt = $ropt[0];

						$label = $ropt->getName();
						if($ropt->_get($pricefield) > 0){
							$label .= ' (+ €' . $ropt->_get($pricefield) . ')';
						}
						$boxes[$ropt->getId()]['name'] = $label;

						$boxes[$ropt->getId()]['selected'] = $opt->isEnabled();

					}
				}

				$form->addField(new checkboxgroupField($optionset->getId(),$optionset->getName(),$boxes));
			}
		}
		elseif($optionset->getOptionsettypeid() == 3){
			$optionid = $optionset->getOptionid();
			$cond = array(
						'AND' => array(
									'optionsetid' => array('mode' => '=', 'value' => $optionset->getId()),
									'optionid' => array('mode' => '=', 'value' => $optionid[0])
							)
			);
			$optionsetoption = $optionsetoptionModel->get($cond);
			if(count($optionsetoption) == 1){
				$optionsetoption = $optionsetoption[0];

				if($optionsetoption->isEnabled()){
					$checked = true;
				}
				else {
					$checked = false;
				}

				$ropt = $optionModel->getfromId($optionid[0]);
				if(count($ropt) > 0){
					$ropt = $ropt[0];

					$label = $optionset->getName();

					if($ropt->_get($pricefield) > 0){
						$label .= ' (+ €' . $ropt->_get($pricefield) . ')';
					}

					$form->addField(new checkboxField($optionset->getId(),$label,$optionid[0],$checked));
				}
			}
		}

		$this->order['optionsets'][$optionset->getId()] = $form->getFieldvalue($optionset->getId());

		if($form->getFieldvalue($optionset->getId()) != ''){
			$value = $form->getFieldvalue($optionset->getId());

			$selectedoption = $optionModel->getfromId($value);
			if(count($selectedoption) > 0){
				$object = $selectedoption[0];

				$this->price += $object->_get($pricefield);

				foreach($object->getOptionsetid() as $optsetid){
					$optset = $optionsetModel->getfromId($optsetid);
					if(count($optset) > 0){
						$ready = $this->resolveOptionset($form, $optset[0]);
					}
				}
			}
		}

		return $ready;
	}

	protected function displaypromo(){

		// Not yet :)
		$this->response->assign('extraordercontainer','innerHTML','');
	}

	protected function hidepromo(){
		$this->response->assign('extraordercontainer','innerHTML','');
	}

	public function order($parameters = array()){
		$view = new ui($this);
		$view->assign('price', $this->price);


		$form = $this->buildform($parameters);
		$form->setNofocus(false);

		if($form->validate() && !$parameters['abort'] == 'true'){
			if($form->confirmed($this, 'Bestellen: Bent u zeker?')){

				$saved = true;
				try {
					$theorder = array();

					$optionsetModel = new mealoptionsetModel();
					$optionModel = new mealoptionModel();
					$optionsetoptionModel = new mealoptionsetoptionModel();
					$mealtypemodel = new mealtypeModel();
					$mealModel = new mealModel();

					$mealtype = $mealtypemodel->getfromId($this->order['mealtypeid']);
					$theorder['mealtype'] = $mealtype[0];

					$meal = $mealModel->getfromId($this->order['mealid']);
					$theorder['meal'] = $meal[0];

					$theorder['uur'] = $this->order['uur'];

					foreach($this->order['optionsets'] as $optionsetid => $selectedoptions){
						$optset = $optionsetModel->getfromId($optionsetid);
						$optset = $optset[0];

						$theorder['optionsets'][$optionsetid]['name'] = $optset->getName();
						$theorder['optionsets'][$optionsetid]['type'] = $optset->getOptionsettypeid();
						$theorder['optionsets'][$optionsetid]['place'] = $optset->getPlace();

						if($optset->getOptionsettypeid() == 1){
							foreach($optset->getOptionid() as $optionid){
								$option = $optionModel->getfromId($optionid);
								$option = $option[0];

								if($optionid == $selectedoptions){
									$theorder['optionsets'][$optionsetid]['options']['wel'][] = $option->getName();
								}
								else {
									$theorder['optionsets'][$optionsetid]['options']['niet'][] = $option->getName();
								}
							}
						}
						elseif ($optset->getOptionsettypeid() == 2){
							foreach($optset->getOptionid() as $optionid){
								$option = $optionModel->getfromId($optionid);
								$option = $option[0];

								if(in_array($optionid,$selectedoptions)){
									$theorder['optionsets'][$optionsetid]['options']['wel'][] = $option->getName();
								}
								else {
									$theorder['optionsets'][$optionsetid]['options']['niet'][] = $option->getName();
								}
							}
						}
						else {
							foreach($optset->getOptionid() as $optionid){
								$option = $optionModel->getfromId($optionid);
								$option = $option[0];

								if($optionid == $selectedoptions){
									$theorder['optionsets'][$optionsetid]['options']['wel'][] = $option->getName();
								}
								else {
									$theorder['optionsets'][$optionsetid]['options']['niet'][] = $option->getName();
								}
							}
						}

					}

					$theorder['price'] = $this->price;

					$view->assign('order' , $theorder);

					$mealorderObject = new mealorderObject();
					$mealorderModel = new mealorderModel();

					$mealorderObject->setMealtype($theorder['mealtype']->getName());
					$mealorderObject->setMeal($theorder['meal']->getName());
					$mealorderObject->setUur($this->order['uur']);
					$mealorderObject->setPrice($this->price);

					$mealorderObject->setPrinted(0);

					$mealorderObject->setOrderuur(time());
					$mealorderObject->setOrderuurtext(date("H:i - d/m/Y",$mealorderObject->getOrderuur()));
					$mealorderObject->setUurtext(date("H:i - d/m/Y",$mealorderObject->getUur()));


					$ordername = $form->getFieldvalue('orderuser');
					$usermodel = new userModel();

					require(FRAMEWORK . DS . 'conf' . DS . 'mycafetaria.php');

					$orderuser = $usermodel->searchnamesforgroup('*' . $ordername . '*', $ordergroupdn);

					if(count($orderuser) == 1){
						$orderuser = $orderuser[0];
						$orderuserid = $orderuser->getId();
					}
					else {
						$orderuserid = -1;
					}

					$mealorderObject->setOrderuserid(myauth::getCurrentuser()->getId());
					$mealorderObject->setOrderuser(myauth::getCurrentuser()->getName());

					$mealorderObject->setUserid($orderuserid);
					$mealorderObject->setUser($ordername);

					$mealorderModel->save($mealorderObject);

					$mealorderoptionsetModel = new mealorderoptionsetModel();
					$mealorderoptionModel = new mealorderoptionModel();
					foreach($theorder['optionsets'] as $optionsetid => $optionset){
						$tmp = new mealorderoptionsetObject();

						$tmp->setMealorderid($mealorderObject->getId());
						$tmp->setOptionset($optionset['name']);
						$tmp->setOptionsettype($optionset['type']);
						$tmp->setPlace($optionset['place']);

						$mealorderoptionsetModel->save($tmp);

						foreach($optionset['options']['wel'] as $option){
							$tmpopt = new mealorderoptionObject();

							$tmpopt->setMealorderoptionsetid($tmp->getId());
							$tmpopt->setOption($option);
							$tmpopt->setSelected(1);

							$mealorderoptionModel->save($tmpopt);
						}

						if($optionset['type'] != 1){
							foreach($optionset['options']['niet'] as $option){
								$tmpopt = new mealorderoptionObject();

								$tmpopt->setMealorderoptionsetid($tmp->getId());
								$tmpopt->setOption($option);
								$tmpopt->setSelected(0);

								$mealorderoptionModel->save($tmpopt);
							}
						}
					}
				}
				catch(Exception $e){
					$x = $e;
					$saved = false;
				}

					if($saved){
						$type = 'success';
					}
					else
					{
						$type = 'error';
					}

					$tmpl = new ui($this);
					$tmpl->assign('saved',$saved);

					$popupcontroller = new popupController();
					$popupcontroller->createflash(array('name' => 'flash_ordered','type' => $type, 'content' => $tmpl->fetch('mycafetaria_orderedflash.tpl')));

					$this->listmyorders(array('orderuser' => $ordername));

					//$this->response->assign($this->self,'innerHTML',$view->fetch('mycafetaria_ordered.tpl'));
			}
		}
		elseif(!$form->isSent() || $parameters['abort'] == 'true')  {
			$view->assign('form' , $form);
			$this->response->assign($this->self,'innerHTML', $view->fetch('mycafetaria_order.tpl'));
		}
		else {
			$this->response->assign('formerror_' . $form->getId(),'innerHTML', 'Gelieve alle benodigde velden correct in te vullen.', true);
		}


	}

	public function listmyorders($parameters = array()){
		$view = new ui($this);

		require(FRAMEWORK . DS . 'conf' . DS . 'mycafetaria.php');

		$form = new form($parameters);

		if(isset($parameters['orderuser'])){
			$currentname = $parameters['orderuser'];
		}
		else {

			$currentuser = myauth::getCurrentuser();
			$currentname = '';
			$groups = $currentuser->getGroupid();

			if(!isset($groups[$algemenegebruikers])){
				$currentname = $currentuser->getName();
			}
		}

		$form->addField(new suggesttextField('mycafetaria', 'listusers', 'orderuser', 'Gebruiker',$currentname, array('required')));
		if($currentname != '' || $form->getFieldvalue('orderuser') != ''){
			$form->setNofocus(true);
		}


		if($form->validate() || (!$form->isSent() && $currentname != '')){

			$name = ($form->getFieldvalue('orderuser') == '')? $currentname : $form->getFieldvalue('orderuser');

			$view->assign('name',$name);

			$grid = new mygrid('myorderlist_' . $name);

			$grid->setModel(new processedmealorderModel());

			$grid->setDefaultorder(array('fields' => array('uur'), 'type' => 'DESC'));
			$grid->setDefaultpagesize(15);
			$grid->setDefaultconditions(array( 'user' => array('mode' => '=', 'value' => $name)));

			$view->assign('myorderlist' , $grid);

			$today = strtotime('today');
			$tomorrow = strtotime('tomorrow');

			$conditions = array('AND' => array(
												array( 'user' => array('mode' => '=', 'value' => $name)),
												array('uur' => array('mode' => 'BETWEEN', 'value' => $today , 'topvalue' => $tomorrow ))
											)
								);

			$model = new processedmealorderModel();
/*
			$grid2 = new mygrid('myorderlist_today_' . $name);
			$grid2->setModel($model);
			$grid2->setDefaultconditions($conditions);
*/
			$view->assign('myorderlisttoday',$grid2);

			$orders = $model->get($conditions);

			$view->assign('orders' , $orders);

			$view->assign('form',$form);

		}
		elseif(!$form->isSent()){
			$view->assign('form',$form);
		}

		$this->response->assign($this->self,'innerHTML', $view->fetch($parameters['viewprefix'] . 'mycafetaria_listmyorders.tpl') );
	}

	public function listallorders($parameters = array()){
		$view = new ui($this);

		$grid = new mygrid('orderlist');

		$grid->setModel(new processedmealorderModel());

		$grid->setDefaultorder(array('fields' => array('uur'), 'type' => 'DESC'));
		$grid->setDefaultpagesize(10);
		$grid->setDefaultconditions('');

		$grid->registerRequest('printed' , 'mycafetaria' , 'reprint' , array('id' => '{id}'));

		$view->assign('orderlist' , $grid);

		$this->response->assign($this->self,'innerHTML', $view->fetch($parameters['viewprefix'] . 'mycafetaria_listallorders.tpl') );
	}

	public function reprint($parameters = array()){
		$sure = $parameters['sure'];
		$popupcontroller = new popupController();

		$model = new mealorderModel();

		$order = $model->getfromId($parameters['id']);

		if(count($order) == 1){
			$order = $order[0];

			if($order->getPrinted()){

				if($sure != 'sure'){
					$template = new ui($this);

					$ja = new ajaxrequest('mycafetaria', 'reprint' , array('id' => $parameters['id'], 'sure' => 'sure'));
					$template->assign('ja' , $ja);

					$popupcontroller->create(array('name' => 'confirm' , 'content' => $template->fetch('mycafetaria_confirmreprint.tpl')));
				}
				else{
					$popupcontroller->destroy(array('name' => 'confirm'));

					$order->setPrinted(0);
					$model->save($order);

					$this->listallorders();
				}
			}
		}
	}

	public function closeextra($parameters = array()){
		$this->response->assign($parameters['id'],'innerHTML', '' );
	}

	public function managemeals($parameters = array()){
		$view = new ui($this);

		$grid = new mygrid('meals');

		$grid->setModel(new processedmealModel());

		$grid->setDefaultorder(array('fields' => array('name'), 'type' => 'ASC'));
		$grid->setDefaultpagesize(10);
		$grid->setDefaultconditions('');

		$view->assign('meals' , $grid);

		$this->response->assign($this->self,'innerHTML',$view->fetch($parameters['viewprefix'] . 'mycafetaria_managemeals.tpl'));
	}

	public function manageoptionsets($parameters = array()){
		$view = new ui($this);

		$grid = new mygrid('optionsets');

		$grid->setModel(new processedmealoptionsetModel());

		$grid->setDefaultorder(array('fields' => array('name'), 'type' => 'ASC'));
		$grid->setDefaultpagesize(10);
		$grid->setDefaultconditions('');

		$grid->registerAddrequest('mycafetaria', 'editoptionset',array('title' => 'Optiegroup toevoegen'));
		$grid->registerEditrequest('mycafetaria', 'editoptionset',array('id' => '{id}','title' => 'Optiegroup aanpassen'));
		$grid->registerDeleterequest('mycafetaria', 'deleteoptionset',array('id' => '{id}','title' => 'Optiegroup verwijderen'));

		$view->assign('optionsets' , $grid);

		$this->response->assign($this->self,'innerHTML',$view->fetch($parameters['viewprefix'] . 'mycafetaria_manageoptionsets.tpl'));
	}

	public function editoptionset($parameters = array()){
		$view = new ui($this);

		$model = new mealoptionsetModel();
		$new = true;

		if(isset($parameters['id'])){
			$optionset = $model->getfromId($parameters['id']);

			if(count($optionset) == 1){
				$optionset = $optionset[0];
				$new = false;
			}
			else {
				$optionset = new mealoptionsetObject();
			}
		}
		else {
			$optionset = new mealoptionsetObject();
		}

		$form = new mygridform($parameters,$parameters['-gridid-'],'edit');

		$form->addField(new textField('name','Naam',$optionset->getName(),array('required')));

		$type = new selectField('type','Type',array('required'));

		$type->addOption(new selectoptionField('','',$new));

		$typemodel = new mealoptionsettypeModel();

		$types = $typemodel->get();

		foreach($types as $typeobj){
			$select = false;
			if(!$new){
				if($optionset->getOptionsettypeid() == $typeobj->getId()){
					$select = true;
				}
			}
			$type->addOption(new selectoptionField($typeobj->getName(),$typeobj->getId(),$select));
		}

		$form->addField($type);

		if(!$new){
			$form->addField(new hiddenField('id',$parameters['id']));
		}

		if($form->validate()){
			$optionset->setName($form->getFieldvalue('name'));
			$optionset->setOptionsettypeid($form->getFieldvalue('type'));

			$model->save($optionset);

			if($new){
				$parameters['id'] = $optionset->getId();
				unset($parameters['hidden_form_id']);
				$parameters['title'] = 'Optiegroep wijzigen';

				$gridcontroller = new mygridController();

				$gridcontroller->editrequest($parameters);

				return true;
			}
			else {
				$this->response->assign('formerror_' . $form->getId() , 'innerHTML' , 'De gegevens zijn aangepast',true);
				return true;
			}

			return true;
		}
		elseif(!$form->isSent()) {
			$view->assign('form', $form);
			return $view->fetch('mycafetaria_editoptionset.tpl');
		}
		else {
			return false;
		}
	}

	public function deleteoptionset($parameters = array()){
		$sure = $parameters['sure'];

		$model = new processedmealoptionsetModel();

		$optionset = $model->getfromId($parameters['id']);

		if(count($optionset) == 1){
			$optionset = $optionset[0];

			if($sure != 'sure'){
				$template = new ui($this);

				$template->assign('optionset',$optionset);

				return $template->fetch('mycafetaria_confirmdeleteoptionset.tpl');
			}
			else{

				$cond = array('id' => array('mode' => '=', 'value' => $optionset->getId()));
				$model->delete($cond);

				return true;
			}
		}
	}

	public function manageoptions($parameters = array()){
		$view = new ui($this);

		$grid = new mygrid('options');

		$grid->setModel(new mealoptionModel());

		$grid->setDefaultorder(array('fields' => array('name'), 'type' => 'ASC'));
		$grid->setDefaultpagesize(10);
		$grid->setDefaultconditions('');

		$grid->registerAddrequest('mycafetaria', 'editoption', array('title' => 'Optie toevoegen'));
		$grid->registerEditrequest('mycafetaria', 'editoption',array('id' => '{id}','title' => 'Optie aanpassen'));
		$grid->registerDeleterequest('mycafetaria', 'deleteoption',array('id' => '{id}','title' => 'Optie verwijderen'));

		$view->assign('options' , $grid);

		$this->response->assign($this->self,'innerHTML',$view->fetch($parameters['viewprefix'] . 'mycafetaria_manageoptions.tpl'));
	}

	public function editoption($parameters = array()){
		$view = new ui($this);

		$model = new mealoptionModel();
		$new = true;

		if(isset($parameters['id'])){
			$option = $model->getfromId($parameters['id']);

			if(count($option) == 1){
				$option = $option[0];
				$new = false;
			}
			else {
				$option = new mealoptionObject();
			}
		}
		else {
			$option = new mealoptionObject();
		}

		$view->assign('new',$new);

		$form = new mygridform($parameters,$parameters['-gridid-'], 'edit');

		if(!$new){
			$form->addField(new textField('name','Naam',$option->getName(),array('required')));
			$form->addField(new textField('price','Prijs',$option->getPrice(),array('required','numeric')));
			$form->addField(new textField('price2','Prijs externen',$option->getPrice2(),array('required','numeric')));
			$form->addField(new hiddenField('id',$parameters['id']));
		}
		else {
			$form->addField(new textField('name','Naam','',array('required')));
			$form->addField(new textField('price','Prijs','',array('required','numeric')));
			$form->addField(new textField('price2','Prijs externen','',array('required','numeric')));
		}


		if($form->validate()){
			$option->setName($form->getFieldvalue('name'));
			$option->setPrice($form->getFieldvalue('price'));
			$option->setPrice2($form->getFieldvalue('price2'));

			$model->save($option);

			if($new){
				$parameters['id'] = $option->getId();
				unset($parameters['hidden_form_id']);
				$parameters['title'] = 'Optie wijzigen';

				$gridcontroller = new mygridController();

				$gridcontroller->editrequest($parameters);

				return true;
			}
			else {
				$this->response->assign('formerror_' . $form->getId() , 'innerHTML' , 'De gegevens zijn aangepast',true);
				return true;
			}
		}
		elseif(!$form->isSent()) {
			$view->assign('form', $form);

			if(!$new){
				$linkgrid = new mygrid('optionoptionset');

				$linkgrid->setModel(new processedmealoptionoptionsetModel());
				$linkgrid->setDefaultorder(array('fields' => array('id'), 'type' => 'ASC'));
				$linkgrid->setDefaultpagesize(10);
				$linkgrid->setDefaultconditions(array('optionid' => array('mode' => '=', 'value' => $option->getId())));

				$linkgrid->registerDeleterequest('mycafetaria', 'deleteoptionoptionsetlink',array('id' => '{id}' , 'title' => 'Link met optiegroep verwijderen'));
				$linkgrid->registerAddrequest('mycafetaria', 'addoptionoptionsetlink',array('optionid' => $option->getId(), 'title' => 'Link met optiegroep toevoegen'));
				//$linkgrid->registerEditrequest('mycafetaria', 'editoptionset',array('id' => '{optionsetid}','title' => 'Optie aanpassen'));

				$view->assign('optionoptionsetgrid', $linkgrid);

			}

			return $view->fetch('mycafetaria_editoption.tpl');
		}
		else {
			return false;
		}
	}

	public function addoptionoptionsetlink($parameters){
		if(isset($parameters['id'])){
			$model = new mealoptionoptionsetModel();

			$link = new mealoptionoptionsetObject();

			$link->setOptionsetid($parameters['id']);
			$link->setOptionid($parameters['optionid']);

			$model->save($link);

			$gridcontroller = new mygridController();

			$gridcontroller->reloadgrid('optionoptionset');

		}
		else {
			$view = new ui($this);

			$grid = new mygrid('addoptionsets');

			$grid->setModel(new processedmealoptionsetModel());

			$grid->setDefaultorder(array('fields' => array('name'), 'type' => 'ASC'));
			$grid->setDefaultpagesize(10);
			$grid->setDefaultconditions('');

			//$grid->registerAddrequest('mycafetaria', 'editoptionset',array('title' => 'Optiegroup toevoegen'));
			$grid->registerEditrequest('mycafetaria', 'editoptionset',array('id' => '{id}','title' => 'Optiegroup aanpassen'));
			//$grid->registerDeleterequest('mycafetaria', 'deleteoptionset',array('id' => '{id}','title' => 'Optiegroup verwijderen'));

			$grid->registerRequest('name','mycafetaria','addoptionoptionsetlink',array_merge(array('id' => '{id}'),$parameters));

			$view->assign('optionsets' , $grid);

			return $view->fetch($parameters['viewprefix'] . 'mycafetaria_addoptionoptionsetlink.tpl');
		}
	}


	public function deleteoptionoptionsetlink($parameters){
		$sure = $parameters['sure'];

		$model = new processedmealoptionoptionsetModel();

		$optionlink = $model->getfromId($parameters['id']);

		if(count($optionlink) == 1){
			$optionlink = $optionlink[0];

			if($sure != 'sure'){
				$template = new ui($this);

				$template->assign('optionlink',$optionlink);

				return $template->fetch('mycafetaria_confirmdeleteoptionoptionsetlink.tpl');
			}
			else{

				$cond = array('id' => array('mode' => '=', 'value' => $optionlink->getId()));
				$model->delete($cond);

				return true;
			}
		}
	}

	public function deleteoption($parameters = array()){
		$sure = $parameters['sure'];

		$model = new mealoptionModel();

		$option = $model->getfromId($parameters['id']);

		if(count($option) == 1){
			$option = $option[0];

			if($sure != 'sure'){
				$template = new ui($this);

				$template->assign('option',$option);

				return $template->fetch('mycafetaria_confirmdeleteoption.tpl');
			}
			else{

				$cond = array('id' => array('mode' => '=', 'value' => $option->getId()));
				$model->delete($cond);

				return true;
			}
		}
	}

	public function managetimes($parameters = array()){
		$view = new ui($this);

		$grid = new mygrid('times');

		$grid->setModel(new processedmealblackoutModel());

		$grid->setDefaultorder(array('fields' => array('days'), 'type' => 'ASC'));
		$grid->setDefaultpagesize(10);
		$grid->setDefaultconditions('');

		$grid->registerAddrequest('mycafetaria', 'edittime',array('title' => 'Tijd toevoegen'));
		$grid->registerEditrequest('mycafetaria', 'edittime',array('id' => '{id}','title' => 'Tijd aanpassen'));
		$grid->registerDeleterequest('mycafetaria', 'deletetime',array('id' => '{id}' , 'title' => 'Tijd verwijderen'));

		$view->assign('times' , $grid);

		$this->response->assign($this->self,'innerHTML',$view->fetch($parameters['viewprefix'] . 'mycafetaria_managetimes.tpl'));
	}

	public function deletetime($parameters = array()){
		$sure = $parameters['sure'];

		$model = new processedmealblackoutModel();

		$blackout = $model->getfromId($parameters['id']);

		if(count($blackout) == 1){
			$blackout = $blackout[0];

			if($sure != 'sure'){
				$template = new ui($this);

				$template->assign('blackout',$blackout);

				$mealmodel = new mealModel();

				if( is_array($blackout->getMealid())){
					$cond = array('id' => array('mode' => 'IN' , 'value' => $blackout->getMealid()));

					$meals = $mealmodel->get($cond);

					$template->assign('meals',$meals);
				}

				return $template->fetch('mycafetaria_confirmdeletetime.tpl');
			}
			else{

				$cond = array('id' => array('mode' => '=', 'value' => $blackout->getId()));
				$model->delete($cond);

				return true;
			}
		}
	}

	public function edittime($parameters = array()){
		$view = new ui($this);

		$model = new mealblackoutModel();
		$new = true;

		if(isset($parameters['id'])){
			$blackout = $model->getfromId($parameters['id']);

			if(count($blackout) == 1){
				$blackout = $blackout[0];
				$new = false;
			}
			else {
				$blackout = new mealblackoutObject();
			}
		}
		else {
			$blackout = new mealblackoutObject();
		}

		$form = new mygridform($parameters,$parameters['-gridid-'], 'edit');

		$today = strtotime('today');

		$start = new selectField('blackoutperiodstart' , 'Starttijd' , array('required'));
		$start->addOption(new selectoptionField('','',!$new));
		for($i = 0; $i < (60*60*24); ($i += (60*30))){
			$uur = $today + $i;
			$select = false;
			if(!$new){
				if( $i == $blackout->getBlackoutperiodstart()){
					$select = true;
				}
			}
			$start->addOption(new selectoptionField(date('H:i',$uur),$i,$select));
		}
		$form->addField($start);

		$end = new selectField('blackoutperiodend' , 'Eindtijd' , array('required'));
		$end->addOption(new selectoptionField('','',!$new));
		for($i = 0; $i <= (60*60*24); ($i += (60*30))){
			$uur = $today + $i;
			$select = false;
			if(!$new){
				if( $i == $blackout->getBlackoutperiodend()){
					$select = true;
				}
			}
			$end->addOption(new selectoptionField(date('H:i',$uur),$i,$select));
		}
		$form->addField($end);

		$trigger = new selectField('triggertime' , 'Trigger tijd' , array('required'));
		$trigger->addOption(new selectoptionField('','',$new));

		$altijd = false;
		if($blackout->getTriggertime() == -1){
			$altijd = true;
		}
		$trigger->addOption(new selectoptionField('Altijd',-1,$altijd));

		for($i = 0; $i <= (60*60*24); ($i += (60*30))){
			$uur = $today + $i;
			$select = false;
			if(!$new){
				if( $i == $blackout->getTriggertime()){
					$select = true;
				}
			}
			$trigger->addOption(new selectoptionField(date('H:i',$uur),$i,$select));
		}
		$form->addField($trigger);

		$days = new selectField('days','Dagen',array('required'));

		switch($blackout->getDays()){
			case 1: $one = true;
					$two = false;
					$three = false;
				break;
			case 2: $one = false;
					$two = true;
					$three = false;
				break;
			case 3: $one = false;
					$two = false;
					$three = true;
				break;
			default:$one = false;
					$two = false;
					$three = false;
		}

		$days->addOption(new selectoptionField('','', $new));

		$days->addOption(new selectoptionField('Weekdagen',1, $one));
		$days->addOption(new selectoptionField('Weekend',2,$two));
		$days->addOption(new selectoptionField('Alle dagen',3,$three));

		$form->addField($days);

		if(!$new){
			$form->addField(new hiddenField('id',$parameters['id']));
		}

		if($form->validate()){
			$blackout->setBlackoutperiodstart($form->getFieldvalue('blackoutperiodstart'));
			$blackout->setBlackoutperiodend($form->getFieldvalue('blackoutperiodend'));
			$blackout->setTriggertime($form->getFieldvalue('triggertime'));
			$blackout->setDays($form->getFieldvalue('days'));

			$model->save($blackout);

			return true;
		}
		elseif(!$form->isSent()) {
			$view->assign('form', $form);

			return $view->fetch('mycafetaria_edittime.tpl');
		}
		else {
			return false;
		}
	}

	public function managepromotions($parameters = array()){
		require(FRAMEWORK . DS . 'conf' . DS . 'mycafetaria.php');
		$view = new ui($this);

		$grid = new mygrid('promotions');
		$grid->setModel(new cafetariapromotieModel());

		$grid->registerEditrequest('mycafetaria','editpromotion',array('title' => 'Promotie aanpassen','id' => '{id}'));

		$view->assign('promotions',$grid);

		$articles = new mygrid('articles_promotions');
		$articles->setModel(new processedmyarticlesectionlinkModel());
		$articles->setDefaultconditions(array('sectionid' => array('mode' => '=','value' => $promotionarticlesection)));
		$articles->setOrderfield('order');

		$articles->registerAddrequest('mycafetaria','addpromotext',array('title' => 'Artikel toevoegen','sectionid' => $promotionarticlesection));
		$articles->registerEditrequest('mycafetaria','editpromotext',array('title' => 'Artikel aanpassen','id' => '{articleid}'));

		$view->assign('articles',$articles);

		$this->response->assign($this->self,'innerHTML', $view->fetch('mycafetaria_managepromotions.tpl'));
	}

	public function editpromotextversion($parameters = array()){
		$view = new ui($this);

		$articlemodel = new myarticleModel();
		$versionmodel = new myarticleversionModel();

		$version = $versionmodel->getfromId($parameters['id']);

		if(count($version) == 1){
			$version = $version[0];
			$form = new mygridform($parameters,$parameters['-gridid-'],'edit');

			$form->addField(new textField('title','Titel',$version->getTitle(),array('required')));
			$form->addField(new datepickerField('start','Gepubliceerd van',true,$version->getStartpublishdate(),array('required')));

			$form->addField(new hiddenField('limit','limit'));
			$form->addField(new datepickerField('stop','Gepubliceerd tot',true,$version->getStoppublishdate(),array('required')));

			$form->addField(new rteField('content','Inhoud',$version->getContent(),array('required')));

			$draft = new selectField('state','Bewaar als',array('required'));
			$draft->addOption(new selectoptionField('Actieve versie','Actief',true));
			$draft->addOption(new selectoptionField('Draft','Draft',false));
			$form->addField($draft);

			$form->addField(new hiddenField('articleid',$parameters['articleid']));
			$form->addField(new hiddenField('id',$parameters['id']));

			if($form->validate()){

				$newversion = new myarticleversionObject();
				$newversion->setArticleid($parameters['articleid']);
				$newversion->setAuthor(myauth::getCurrentuser()->getId());
				$newversion->setAuthorname(myauth::getCurrentuser()->getName());
				$newversion->setCreationdate(time());
				$newversion->setTitle($form->getFieldvalue('title'));
				$newversion->setState($form->getFieldvalue('state'));
				$newversion->setStartpublishdate($form->getFieldvalue('start'));
				$newversion->setContent($form->getFieldvalue('content'));

				if($form->getFieldvalue('limit') == 'limit'){
					$newversion->setStoppublishdate($form->getFieldvalue('stop'));
				}
				else {
					$newversion->setStoppublishdate(-1);
				}

				try {
					if($form->getFieldvalue('state') == 'Actief'){
						$articleidcond = array('articleid'  => array('mode' => '=', 'value' => $parameters['articleid']));
						$statecond = array('state' => array('mode' => '=','value' => 'Actief'));
						$prevactive = $versionmodel->get(array('AND' => array($articleidcond,$statecond)));

						foreach($prevactive as $prev){ // This could have been if equal to 1 and just do the one, but this method is "self-healing" if multiple versions get flagged active
							$prev->setState('Gearchiveerd');
							$versionmodel->save($prev);
						}
					}

					$versionmodel->save($newversion);

				}
				catch (Exception $e){
					$flash = new popupController();
					$flash->createflash(array('name' => 'erroredit','type'=> 'error','content' => 'De gegevens werden niet aangepast! Raadpleeg de informaticadienst.'));
					return false;
				}

				$flash = new popupController();
				$flash->createflash(array('name' => 'flash_add_' . $parameters['-gridid-'],'type' => 'success', 'content' => 'De gegevens zijn goed aangepast.'));

				$this->response->assign('gridextra_' . $parameters['-gridid-'],'innerHTML','');

				return true;
			}
			elseif(!$form->isSent()){
				$view->assign('form',$form);
				return $view->fetch('myarticle_editversion.tpl');
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}

	public function editpromotext($parameters = array()){
		$view = new ui($this);

		$articlemodel = new myarticleModel();
		$versionmodel = new myarticleversionModel();

		$article = $articlemodel->getfromId($parameters['id']);

		if(count($article) == 1){
			$article = $article[0];

			$view->assign('article',$article);

			$aliasform = new mygridform($parameters,$parameters['-gridid-'],'edit');

			$aliasform->addField(new textField('alias','Werktitel',$article->getAlias(),array('required')));
			$aliasform->addField(new hiddenField('id',$parameters['id']));
			$aliasform->addField(new hiddenField('title',$parameters['title']));


			if($aliasform->validate()){
				$article->setAlias($aliasform->getFieldvalue('alias'));

				$flash = new popupController();

				try {
					$articlemodel->save($article);
				}
				catch(Exception $e){
					$flash->createflash(array('name' => 'error','type' => 'error', 'content' => 'De aanpassing werd niet doorgevoerd! Contacteer de informaticadienst.'));
					return false;
				}

				$flash->createflash(array('name' => 'error','type' => 'success', 'content' => 'De aanpassing werd goed doorgevoerd.'));

				$gridcontroller = new mygridController();


				unset($parameters['hidden_form_id']);
				$parameters['alias'] = '';
				$gridcontroller->editrequest($parameters);

				return true;
			}
			elseif(!$aliasform->isSent()){
				$view->assign('aliasform',$aliasform);
			}
			else {
				return false;
			}


			$versionsids = $article->getVersion();

			$grid = new mygrid('articleversions-' . $article->getId());
			$grid->setModel(new myarticleversionModel());

			$idcond = array('articleid' => array('mode' => '=','value'=>$parameters['id']));


			$grid->setDefaultconditions($idcond);
			$grid->setDefaultorder(array('fields' => array('state', 'creationdate'), 'type' => 'DESC'));

			$grid->registerEditrequest('mycafetaria','editpromotextversion',array('id' => '{id}','articleid' => $parameters['id'] ,'title' => 'Versie aanpassen'));

			$view->assign('grid',$grid);

			return $view->fetch('myarticle_editarticle.tpl');
		}
		else {
			return false;
		}
	}

	public function addpromotext($parameters = array()){

	$view = new ui($this);

		$form = new mygridform($parameters,$parameters['-gridid-'],'edit');

		$form->addField(new textField('title','Titel','',array('required')));
		$form->addField(new textField('alias','Werktitel',''));
		$form->addField(new datepickerField('start','Gepubliceerd van',true,'',array('required')));
		$form->addField(new hiddenField('limit','limit'));
		$form->addField(new datepickerField('stop','Gepubliceerd tot',true,'',array('required')));
		$form->addField(new rteField('content','Inhoud','',array('required')));

		$draft = new selectField('state','Bewaar als',array('required'));
		$draft->addOption(new selectoptionField('Actieve versie','Actief',true));
		$draft->addOption(new selectoptionField('Draft','Draft',false));
		$form->addField($draft);

		$form->addField(new hiddenField('sectionid',$parameters['sectionid']));

		if($form->validate()){
			$newarticle = new myarticleObject();
			$newarticle->setAuthor(myauth::getCurrentuser()->getId());
			$newarticle->setAuthorname(myauth::getCurrentuser()->getName());
			$newarticle->setCreationdate(time());
			if($form->getFieldvalue('alias') != ''){
				$newarticle->setAlias($form->getFieldvalue('alias'));
			}
			else {
				$newarticle->setAlias($form->getFieldvalue('title'));
			}

			$newversion = new myarticleversionObject();
			$newversion->setAuthor($newarticle->getAuthor());
			$newversion->setAuthorname($newarticle->getAuthorname());
			$newversion->setCreationdate($newarticle->getCreationdate());
			$newversion->setTitle($form->getFieldvalue('title'));
			$newversion->setState($form->getFieldvalue('state'));
			$newversion->setStartpublishdate($form->getFieldvalue('start'));
			$newversion->setContent($form->getFieldvalue('content'));

			if($form->getFieldvalue('limit') == 'limit'){
				$newversion->setStoppublishdate($form->getFieldvalue('stop'));
			}
			else {
				$newversion->setStoppublishdate(-1);
			}

			try{
				$articlemodel = new myarticleModel();
				$versionmodel = new myarticleversionModel();
				$linkmodel = new myarticlesectionlinkModel();

				$articlemodel->save($newarticle);

				$newversion->setArticleid($newarticle->getId());
				$versionmodel->save($newversion);

				$newlink = new myarticlesectionlinkObject();
				$newlink->setArticleid($newarticle->getId());
				$newlink->setSectionid($parameters['sectionid']);
				$newlink->setOrder($linkmodel->getmax('order',array('sectionid' => array('mode' => '=', 'value' => $parameters['sectionid']))) + 1);

				$linkmodel->save($newlink);
			}
			catch (Exception $e){
				$flash = new popupController();
				$flash->createflash(array('name' => 'erroredit','type'=> 'error','content' => 'De gegevens werden niet toegevoegd! Raadpleeg de informaticadienst.'));
				return false;
			}



			$flash = new popupController();
			$flash->createflash(array('name' => 'flash_add_' . $parameters['-gridid-'],'type' => 'success', 'content' => 'De gegevens zijn goed toegevoegd.'));

			$this->response->assign('gridextra_' . $parameters['-gridid-'],'innerHTML','');

			return true;
		}
		elseif(!$form->isSent()){
			$view->assign('form',$form);
			return $view->fetch('myarticle_addarticle.tpl');
		}
		else {
			return false;
		}
	}

	public function editpromotion($parameters = array()){
		$view = new ui($this);
		$model = new cafetariapromotieModel();

		$promo = $model->getfromId($parameters['id']);
		if(count($promo) == 1){
			$promo = $promo[0];

			$form = new mygridform($parameters,'promotions','edit');

			$form->addField(new datepickerField('start','Start',true,$promo->getStarttime(),array('required')));
			$form->addField(new datepickerField('stop','Einde',true,$promo->getStoptime(),array('required')));

			$form->addField(new hiddenField('id',$parameters['id']));

			if($form->validate()){
				$promo->setStarttime($form->getFieldvalue('start'));
				$promo->setStoptime($form->getFieldvalue('stop'));

				try {
					$model->save($promo);
				}
				catch(Exception $e){
					$flash = new popupController();
					$flash->createflash(array('name' => 'error','type' => 'error','content' => 'De gegevens zijn niet aangepast! Raadpleeg de informaticadienst.'));
					return false;
				}

				$flash = new popupController();
				$flash->createflash(array('name' => 'success','type' => 'success','content' => 'De gegevens zijn goed aangepast.'));

				$this->response->assign('gridextra_' . $parameters['-gridid-'],'innerHTML','');

				return true;
			}
			elseif(!$form->isSent()){
				$view->assign('form',$form);
				return $view->fetch('mycafetaria_editpromotion.tpl');
			}
		}
	}
}

?>