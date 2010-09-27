<?php
class myarticleController extends controller {

	public function removedwaypoint($key, $action,$parameters,$currenthashpoints){

		if(!isset($currenthashpoints[$key])){
			$section = $parameters['section'];
			$parameters['article'] = $_SESSION['startarticle_wiki_' . $section];

			$this->showwiki($parameters);
		}

	}

	protected function addWikibreadcrumb($sectionid,$article){
		$_SESSION['breadcrumbs_' . $sectionid][] = $article;
	}

	protected function getWikibreadcrumbs($sectionid){
		$tmp = $_SESSION['breadcrumbs_' . $sectionid];
		$ret = array();
		$i = 0;
		foreach($tmp as $link){
			$tmpreq = new ajaxrequest('myarticle','followwikibreadcrumb', array('section' => $sectionid, 'id' => $link[0],'breadcrumb' => $i));
			$ret[] = array($tmpreq,$link[1]);
			$i++;
		}

		return $ret;
	}

	public function followwikibreadcrumb($parameters = array()){
		$view = new ui($this);

	if(!$parameters['history']){
			$this->response->addWaypoint('myarticle','followwikibreadcrumb','wiki_' . $parameters['section'], $parameters);
		}

		$model = new myarticleModel();

		$cond = array( 'id' => array('mode' => '=', 'value' => $parameters['id']));

		$article = $model->get($cond);

		$sectionid = $parameters['section'];
		$section = new myarticlesectionObject();
		$section->setId($sectionid);

		if(count($article) == 1){
			$article = $article[0];

			if(in_array($parameters['section'],$article->getSection())){

				$versionmodel = new myarticleversionModel();

				$statecond = array('state' => array('mode' => '=', 'value' => 'Actief'));
				$idcond = array('articleid' => array('mode' => '=', 'value' => $article->getId()));

				$cond = array('AND' => array($idcond,$statecond));

				$version = $versionmodel->get($cond);
				if(count($version) == 1){
					$version = $version[0];
					$view->assign('article',$version);
					$view->assign('sectionid',$sectionid);

					if(isset($parameters['breadcrumb'])){
						$cur = $_SESSION['breadcrumbs_' . $sectionid];
						$_SESSION['breadcrumbs_' . $sectionid] = array();
						for($i = 0 ; $i <=  $parameters['breadcrumb']; $i++){
							$_SESSION['breadcrumbs_' . $sectionid][] = $cur[$i];
						}
					}

					$view->assign('breadcrumbs',$this->getWikibreadcrumbs($sectionid));

					if(myacl::isAllowed(myauth::getCurrentuser(),$section,'manage_articlelinks')){
						$req = new ajaxrequest('myarticle','wikieditarticle',array('id' => $article->getId(), 'id' => $article->getId(), 'section' => $sectionid));
						$view->assign('editrequest',$req);
					}

					$view->assign('searchform',$this->searchwiki(array('id' => $article->getId(), 'section' => $sectionid)));

					$this->response->assign('wiki_' . $sectionid, 'innerHTML', $view->fetch('myarticle_showwikiarticle.tpl'));
				}
			}
		}
		else {
			$flash = new popupController();
			$flash->createflash(array('name' => 'warning', 'type' => 'warning', 'content' => 'Deze link wijst naar een pagina die nog niet bestaat.'));
		}

	}

	public function followwikilink($parameters = array()){
		$view = new ui($this);

		if(!$parameters['history']){
			$this->response->addWaypoint('myarticle','followwikilink','wiki_' . $parameters['section'], $parameters);
		}

		$model = new myarticleModel();

		$sectionid = $parameters['section'];
		$section = new myarticlesectionObject();
		$section->setId($sectionid);

		$cond =  array('alias' => array('mode' => '=', 'value' => $parameters['name']));

		$articles = $model->get($cond);

		if(count($articles) > 0){
			$found = false;
			foreach($articles as $article){

				if(in_array($parameters['section'],$article->getSection())){

					$versionmodel = new myarticleversionModel();

					$statecond = array('state' => array('mode' => '=', 'value' => 'Actief'));
					$idcond = array('articleid' => array('mode' => '=', 'value' => $article->getId()));

					$cond = array('AND' => array($idcond,$statecond));

					$version = $versionmodel->get($cond);
					if(count($version) == 1){
						$version = $version[0];
						$view->assign('article',$version);
						$sectionid = $parameters['section'];
						$view->assign('sectionid',$sectionid);

						$this->addWikibreadcrumb($sectionid,array($article->getId() , $version->getTitle()));

						$view->assign('breadcrumbs',$this->getWikibreadcrumbs($sectionid));

						if(myacl::isAllowed(myauth::getCurrentuser(),$section,'manage_articlelinks')){
							$req = new ajaxrequest('myarticle','wikieditarticle',array('id' => $article->getId(), 'id' => $article->getId(), 'section' => $sectionid));
							$view->assign('editrequest',$req);
						}

						$view->assign('searchform',$this->searchwiki(array('id' => $article->getId(), 'section' => $sectionid)));

						$this->response->assign('wiki_' . $sectionid , 'innerHTML', $view->fetch('myarticle_showwikiarticle.tpl'));
						$found = true;
						break;
					}
				}
			}

			if(!$found){
				if(myacl::isAllowed(myauth::getCurrentuser(),$section,'manage_articlelinks')){
					$this->wikiaddArticle(array('section' => $sectionid, 'alias' => $parameters['name'], 'title' => $parameters['title'], 'id' => $parameters['curarticle']));
				}
				else {
					$flash = new popupController();
					$flash->createflash(array('name' => 'warning', 'type' => 'warning', 'content' => 'Deze link wijst naar een pagina die nog niet bestaat.'));
				}
			}
		}
		else {
			if(myacl::isAllowed(myauth::getCurrentuser(),$section,'manage_articlelinks')){
				$this->wikiaddArticle(array('section' => $sectionid, 'alias' => $parameters['name'], 'title' => $parameters['title'], 'id' => $parameters['curarticle']));
			}
			else {
				$flash = new popupController();
				$flash->createflash(array('name' => 'warning', 'type' => 'warning', 'content' => 'Deze link wijst naar een pagina die nog niet bestaat.'));
			}
		}
	}

	public function showwiki($parameters = array()){
		$view = new ui($this);



		$sectionid = $parameters['section'];
		$section = new myarticlesectionObject();
		$section->setId($sectionid);

		$model = new myarticleModel();

		$cond = array( 'AND' => array(
										'id' => array('mode' => '=', 'value' => $parameters['article'])
									)
		);

		$article = $model->get($cond);

		if(count($article) == 1){
			$article = $article[0];

			$_SESSION['startarticle_wiki_' . $sectionid] = $parameters['article'];

			$versionmodel = new myarticleversionModel();

			$statecond = array('state' => array('mode' => '=', 'value' => 'Actief'));
			$idcond = array('articleid' => array('mode' => '=', 'value' => $article->getId()));

			$cond = array('AND' => array($idcond,$statecond));

			$version = $versionmodel->get($cond);
			if(count($version) == 1){
				$version = $version[0];
				$view->assign('article',$version);
				$view->assign('sectionid',$sectionid);

				$_SESSION['breadcrumbs_' . $sectionid] = array();
				$this->addWikibreadcrumb($sectionid,array($article->getId() , $version->getTitle()));

				$view->assign('breadcrumbs',$this->getWikibreadcrumbs($sectionid));

				if(myacl::isAllowed(myauth::getCurrentuser(),$section,'manage_articlelinks')){
					$req = new ajaxrequest('myarticle','wikieditarticle',array('id' => $article->getId(), 'section' => $sectionid));
					$view->assign('editrequest',$req);
				}

				$view->assign('searchform',$this->searchwiki(array('id' => $article->getId(), 'section' => $sectionid)));

				$this->response->assign($this->self, 'innerHTML', '<div id="wiki_' . $sectionid . '">' . $view->fetch('myarticle_showwikiarticle.tpl') . '</div>');
			}
		}
		else {
			$flash = new popupController();
			$flash->createflash(array('name' => 'warning', 'type' => 'warning', 'content' => 'Deze link wijst naar een pagina die nog niet bestaat.'));
		}
	}

	public function searchwiki($parameters = array()){
		$form = new form($parameters);

		$sectionid = $parameters['section'];
		$id = $parameters['id'];

		$form->addField(new inlinetextField('search','Doorzoek de wiki ...','',array('required')));
		$form->addField(new hiddenField('id',$id));
		$form->addField(new hiddenField('section',$sectionid));

		if($form->validate()){

			$view = new ui($this);

			if(!$parameters['history']){
				$this->response->addWaypoint('myarticle','searchwiki','wiki_' . $parameters['section'], $parameters);
			}

			$closerequest = new ajaxrequest('myarticle','followwikibreadcrumb',array('id' => $parameters['id'],'section' => $parameters['section']));
			$view->assign('closerequest',$closerequest);

			$grid = new mygrid('wikisearch_' . $sectionid);
			$grid->setModel(new processedmyarticlesectionlinkModel());

			$sectioncond = array('sectionid' => array('mode' => '=' , 'value' => $sectionid));

			$grid->setDefaultconditions($sectioncond);

			$grid->registerRequest('alias','myarticle','followwikilink',array('name' => '{alias}', 'section' => $sectionid));

			$view->assign('grid',$grid);

			$this->response->assign('wiki_' . $sectionid , 'innerHTML', $view->fetch('myarticle_wikisearch.tpl'));

			$controller = new mygridController();

			$controller->clearsearch(array('gridid' => $grid->getId(), 'directsearch' => 'true'));
			$controller->search(array('gridid' => $grid->getId(),'search' => $parameters['search'], 'directsearch' => 'true'));
		}
		elseif(!$form->isSent()) {
			return $form;
		}
	}

	public function showSection($parameters = array()){
		$view = new ui($this);

		$sectionmodel = new myarticlesectionModel();
		$section = $sectionmodel->getfromId($parameters['section']);

		if(count($section) == 1){
			$section = $section[0];

			$model = new myarticleModel();

			$articles = array();
			foreach($section->getOrder() as $id => $order){
				$tmp = $model->getfromId($id);
				if(count($tmp) == 1){
					$articles[$order] = $tmp[0];
				}
			}


			$versionmodel = new myarticleversionModel();

			$statecond = array('state' => array('mode' => '=', 'value' => 'Actief'));
			$starttimecond = array('startpublishdate' => array('mode' => '<' , 'value' => time()));
			$stoptimecond = array( 'OR' => array(
											array('stoppublishdate' => array('mode' => '>' , 'value' => time())),
											array('stoppublishdate' => array('mode' => '=' , 'value' => -1)),
											)
								);

			$publishedversions = array();
			for($i = 1; $i <= count($articles); $i++){
				$article = $articles[$i];
				$idcond = array('articleid' => array('mode' => '=', 'value' => $article->getId()));

				$cond = array('AND' => array($idcond,$statecond,$starttimecond,$stoptimecond));
				$version = $versionmodel->get($cond);
				if(count($version) == 1){
					$publishedversions[] = $version[0];
				}
			}

			$view->assign('articles' , $publishedversions);

			$this->response->assign($this->self, 'innerHTML', $view->fetch('myarticle_showsection.tpl'));
		}
	}

	public function listSections($parameters = array()){
		$view = new ui($this);

		$grid = new mygrid('myarticlesections');
		$grid->setModel(new myarticlesectionModel());
		$grid->setDefaultorder(array('fields' => array('name'),'type' => 'ASC'));

		$grid->registerEditrequest('myarticle','editSection',array('id' => '{id}','title' => 'Sectie aanpassen', 'myacl' => array('target' => '{this}', 'right' => 'edit', 'default' => false)));
		$grid->registerAddrequest('myarticle','addSection',array('title' => 'Sectie toevoegen', 'myacl' => array('target' => new securitytarget('myarticlesection'), 'right' => 'addsection', 'default' => false)));
		$grid->registerDeleterequest('myarticle','deleteSection',array('id' => '{id}','title' => 'Verwijder sectie', 'myacl' => array('target' => '{this}', 'right' => 'delete', 'default' => false)));

		$view->assign('grid',$grid);

		$this->response->assign($this->self,'innerHTML',$view->fetch('myarticle_listsections.tpl'));

		$aclcontroller = new myaclController();
		$aclcontroller->listacl(array('targetoutput' => 'acllist_listsections', 'objecttype' => 'securitytarget','objectid'=> 'myarticlesection'));
	}

	public function editSection($parameters = array()){
		$view = new ui($this);

		$form = new mygridform($parameters,$parameters['-gridid-'],'edit');

		$model = new myarticlesectionModel();

		$section = $model->getfromId($parameters['id']);

		if(count($section) == 1){
			$section = $section[0];

			$form->addField(new textField('name','Sectie',$section->getName(),array('required')));
			$form->addField(new hiddenField('id',$parameters['id']));

			if($form->validate()){
				$section->setName($form->getFieldvalue('name'));

				try {
					$model->save($section);
				}
				catch(Exception $e){
					$flash = new popupController();
					$flash->createflash(array('name' => 'erroredit','type'=> 'error','content' => 'De gegevens werden niet aangepast! Raadpleeg de informaticadienst.'));
					return false;
				}

				$flash = new popupController();
				$flash->createflash(array('name' => 'flash_edit_' . $parameters['-gridid-'],'type' => 'success', 'content' => 'De gegevens zijn goed aangepast.'));

				return true;
			}
			elseif(!$form->isSent()){
				$view->assign('form',$form);

				if(myacl::isAllowed(myauth::getCurrentuser(),$section,'manage_articlelinks',false)){
					$grid = new mygrid('articles_' . $section->getId());
					$grid->setModel(new processedmyarticlesectionlinkModel());
					$grid->setDefaultconditions(array('sectionid' => array('mode' => '=','value' => $section->getId())));
					$grid->setDefaultorder(array('fields' => array('order'), 'type' => 'ASC'));
					$grid->setOrderfield('order');

					$grid->registerAddrequest('myarticle','addarticlelink',array('title' => 'Artikel linken','sectionid' => $section->getId()));
					$grid->registerDeleterequest('myarticle','deletearticlelink',array('title' => 'Link met artikel verwijderen','id' => '{id}'));
					$grid->registerEditrequest('myarticle','editArticle',array('id' => '{articleid}','title' => 'Artikel aanpassen', 'myacl' => array('target' => '{this}', 'right' => 'edit', 'default' => false)));

					$view->assign('grid',$grid);
				}

				$aclcontroller = new myaclController();
				$acllist = $aclcontroller->listacl(array('targetoutput' => '_return_', 'objecttype' => 'myarticlesectionObject','objectid'=> $parameters['id']));

				$view->assign('section',$section);
				$view->assign('acllist',$acllist);

				return $view->fetch('myarticle_editsection.tpl');
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}

	public function deletearticlelink($parameters = array()){
		if($parameters['sure'] == 'sure'){
			$model = new myarticlesectionlinkModel();
			$flash = new popupController();

			$curlink = $model->getfromId($parameters['id']);
			if(count($curlink) == 1){
				$curlink = $curlink[0];
				try {


					$model->deletebyId($parameters['id']);
				}
				catch(Exception $e){
					$flash->createflash(array('name' => 'error','type' => 'error', 'content' => 'De aanpassing werd niet doorgevoerd! Contacteer de informaticadienst.'));
					return false;
				}
			}
			else {
				$flash->createflash(array('name' => 'warning','type' => 'warning', 'content' => 'De aanpassing werd niet doorgevoerd omdat deze link reeds verwijderd was!'));
				return false;
			}

			$flash->createflash(array('name' => 'error','type' => 'success', 'content' => 'De aanpassing werd goed doorgevoerd.'));
			return true;
		}
		else {
			$model = new processedmyarticlesectionlinkModel();
			$link = $model->getfromId($parameters['id']);
			if(count($link) == 1){
				$view = new ui($this);

				$view->assign('link',$link[0]);

				return $view->fetch('myarticle_deletearticlelink.tpl');
			}
		}
	}

	public function addarticlelink($parameters = array()){
		if(isset($parameters['articleid'])){
			$newlink = new myarticlesectionlinkObject();
			$newlink->setArticleid($parameters['articleid']);
			$newlink->setSectionid($parameters['sectionid']);

			$model = new myarticlesectionlinkModel();

			$newlink->setOrder($model->getmax('order',array('sectionid' => array('mode' => '=', 'value' => $parameters['sectionid']))) + 1);

			$flash = new popupController();

			$testcond = array('AND' => array(array('sectionid' => array('mode' => '=', 'value' => $parameters['sectionid'])), array('articleid' => array('mode' => '=','value' => $parameters['articleid']))));
			if(count($model->get($testcond)) > 0){
				$flash->createflash(array('name' => 'warning','type' => 'warning', 'content' => 'Dit artikel was reeds gelinked'));
				return false;
			}

			try {
				$model->save($newlink);
			}
			catch (Exception $e){
				$flash->createflash(array('name' => 'error','type' => 'error', 'content' => 'De aanpassing werd niet doorgevoerd! Contacteer de informaticadienst.'));
				return false;
			}

			$flash->createflash(array('name' => 'error','type' => 'success', 'content' => 'De aanpassing werd goed doorgevoerd.'));

			$gridcontr = new mygridController();
			$gridcontr->reloadgrid($parameters['oldgrid']);

			return true;
		}
		else {
			$view = new ui($this);

			$grid = new mygrid('articles');
			$grid->setModel(new myarticleModel());

			$grid->registerEditrequest('myarticle','editArticle',array('id' => '{id}','title' => 'Artikel aanpassen', 'myacl' => array('target' => '{this}', 'right' => 'edit', 'default' => false)));
			$grid->registerAddrequest('myarticle','addArticle',array('title' => 'Artikel toevoegen', 'linksection' => $parameters['sectionid'], 'oldgrid' => $parameters['-gridid-'] , 'myacl' => array('target' => new securitytarget('myarticle'), 'right' => 'addarticle', 'default' => false)));
			$grid->registerDeleterequest('myarticle','deletearticle',array('id' => '{id}','title' => 'Artikel verwijdere', 'myacl' => array('target' => '{this}', 'right' => 'delete', 'default' => false)));


			$grid->registerRequest('alias','myarticle','addarticlelink',array('articleid' => '{id}','sectionid' => $parameters['sectionid'], 'oldgrid' => $parameters['-gridid-'], 'myacl' => array('target' => '{this}' , 'right' => 'manage_sectionlinks','default' => false)));

			$view->assign('grid',$grid);

			return $view->fetch('myarticle_addarticlelink.tpl');
		}
	}

	public function addSection($parameters = array()){
		$view = new ui($this);

		$form = new mygridform($parameters,$parameters['-gridid-'],'edit');

		$form->addField(new textField('name','Sectie','',array('required')));

		if($form->validate()){
			$newsection = new myarticlesectionObject();
			$newsection->setName($form->getFieldvalue('name'));

			$model = new myarticlesectionModel();
			try {
				$model->save($newsection);
			}
			catch(Exception $e){
				$flash = new popupController();
				$flash->createflash(array('name' => 'erroredit','type'=> 'error','content' => 'De gegevens werden niet aangepast! Raadpleeg de informaticadienst.'));
				return false;
			}

			$flash = new popupController();
				$flash->createflash(array('name' => 'flash_add_' . $parameters['-gridid-'],'type' => 'success', 'content' => 'De gegevens zijn goed toegevoegd.'));

			try{
				myacl::setAcl(myauth::getCurrentuser(),$newsection,'edit',1);
				myacl::setAcl(myauth::getCurrentuser(),$newsection,'delete',1);
				myacl::setAcl(myauth::getCurrentuser(),$newsection,'manage_articlelinks',1);
			}
			catch (Exception $e){
				$flash->createflash(array('name' => 'flash_add_' . $parameters['-gridid-'],'type' => 'error', 'content' => 'De gegevens zijn goed toegevoegd maar de rechten zijn niet toegekend! Raadpleeg de informaticadienst.' . $e->getMessage()));
			}

			$gridcontroller = new mygridController();

			$parameters['action'] = 'editsection';
			$parameters['controller'] = 'myarticle';
			$parameters['id'] = $newsection->getId();
			$parameters['title'] = 'Sectie aanpassen';
			unset($parameters['hidden_form_id']);
			$parameters['name'] = '';
			$gridcontroller->editrequest($parameters);

			return true;
		}
		elseif(!$form->isSent()){
			$view->assign('form',$form);
			return $view->fetch('myarticle_addsection.tpl');
		}
		else {
			return false;
		}

	}

	public function deleteSection($parameters = array()){
		$view = new ui($this);

		if($parameters['sure'] != 'sure'){
			$model = new myarticlesectionModel();

			$section = $model->getfromId($parameters['id']);

			if(count($section) == 1){
				$section = $section[0];

				$view->assign('section',$section);

				$articlemodel = new myarticleModel();

				$linked = '';
				if(count($section->getArticleid()) > 0){
					$cond = array('id' => array('mode' => 'IN' , 'value' => $section->getArticleid()));
					$linked = $articlemodel->get($cond);
				}

				$view->assign('linked',$linked);

				return $view->fetch('myarticle_deletesection.tpl');
			}
		}
		else {
			$model = new myarticlesectionModel();

			try{
				$model->delete(array('id' => array('mode' => '=','value' => $parameters['id'])));
			}
			catch( Exception $e){
				$flash = new popupController();
				$flash->createflash(array('name' => 'erroredit','type'=> 'error','content' => 'De gegevens werden niet aangepast! Raadpleeg de informaticadienst.'));
				return false;
			}

			$flash = new popupController();
				$flash->createflash(array('name' => 'flash_del_' . $parameters['-gridid-'],'type' => 'success', 'content' => 'De gegevens zijn goed verwijderd.'));

			return true;
		}
	}

	public function listArticles($parameters = array()){
		$view = new ui($this);

		$grid = new mygrid('listarticles');

		$grid->setModel(new myarticleModel());

		$grid->registerEditrequest('myarticle','editArticle',array('id' => '{id}','title' => 'Artikel aanpassen', 'myacl' => array('target' => '{this}', 'right' => 'edit', 'default' => false)));
		$grid->registerAddrequest('myarticle','addArticle',array('title' => 'Artikel toevoegen', 'myacl' => array('target' => new securitytarget('myarticle'), 'right' => 'addarticle', 'default' => false)));
		$grid->registerDeleterequest('myarticle','deletearticle',array('id' => '{id}','title' => 'Artikel verwijdere', 'myacl' => array('target' => '{this}', 'right' => 'delete', 'default' => false)));


		$view->assign('grid',$grid);

		//TODO: make this more acl aware
		if(myacl::isAllowed(myauth::getCurrentuser(),new securitytarget('myarticle'),'managerights')){
			$aclcontroller = new myaclController();
			$view->assign('acllist',$aclcontroller->listacl(array('targetoutput' => '_return_', 'objecttype' => 'securitytarget','objectid'=> 'myarticle')));
		}

		$this->response->assign($this->self,'innerHTML',$view->fetch('myarticle_listarticles.tpl'));

	}

	public function addArticle($parameters = array()){
		$view = new ui($this);

		$form = new mygridform($parameters,$parameters['-gridid-'],'edit');

		$form->addField(new textField('title','Titel','',array('required')));
		$form->addField(new textField('alias','Werktitel',''));
		$form->addField(new datepickerField('start','Gepubliceerd van',true,'',array('required')));
		$form->addField(new checkboxField('limit','Publicatie gelimiteerd in tijd','limit'));
		$form->addField(new datepickerField('stop','Gepubliceerd tot',true,'',array('required')));
		$form->addField(new rteField('content','Inhoud','',array('required')));

		$draft = new selectField('state','Bewaar als',array('required'));
		$draft->addOption(new selectoptionField('Actieve versie','Actief',true));
		$draft->addOption(new selectoptionField('Draft','Draft',false));
		$form->addField($draft);

		if(isset($parameters['oldgrid'])){
			$form->addField(new hiddenField('oldgrid',$parameters['oldgrid']));
		}
		if(isset($parameters['linksection'])){
			$form->addField(new hiddenField('linksection',$parameters['linksection']));
		}

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

				$articlemodel->save($newarticle);

				$newversion->setArticleid($newarticle->getId());
				$versionmodel->save($newversion);

				if(isset($parameters['linksection'])){
					$linkmodel = new myarticlesectionlinkModel();
					$newlink = new myarticlesectionlinkObject();
					$newlink->setArticleid($newarticle->getId());
					$newlink->setSectionid($parameters['linksection']);
					$newlink->setOrder($linkmodel->getmax('order',array('sectionid' => array('mode' => '=', 'value' => $parameters['linksection']))) + 1);


					$linkmodel->save($newlink);
				}
			}
			catch (Exception $e){
				$flash = new popupController();
				$flash->createflash(array('name' => 'erroredit','type'=> 'error','content' => 'De gegevens werden niet toegevoegd! Raadpleeg de informaticadienst.'));
				return false;
			}

			$flash = new popupController();
			$flash->createflash(array('name' => 'flash_add_' . $parameters['-gridid-'],'type' => 'success', 'content' => 'De gegevens zijn goed toegevoegd.'));

			// Rechten geven

			try{
				myacl::setAcl(myauth::getCurrentuser(),$newarticle,'edit',1);
				myacl::setAcl(myauth::getCurrentuser(),$newarticle,'create_newversion',1);
				myacl::setAcl(myauth::getCurrentuser(),$newarticle,'manage_sectionlinks',1);
				myacl::setAcl(myauth::getCurrentuser(),$newarticle,'managerights',1);
			}
			catch (Exception $e){
				$flash->createflash(array('name' => 'flash_add_' . $parameters['-gridid-'],'type' => 'error', 'content' => 'De gegevens zijn goed toegevoegd maar de rechten zijn niet toegekend! Raadpleeg de informaticadienst.' . $e->getMessage()));
			}

			$gridcontroller = new mygridController();

			if(!isset($parameters['linksection'])){

				$parameters['action'] = 'editarticle';
				$parameters['controller'] = 'myarticle';
				$parameters['id'] = $newarticle->getId();
				$parameters['title'] = 'Artikel aanpassen';
				unset($parameters['hidden_form_id']);
				$parameters['name'] = '';
				$gridcontroller->editrequest($parameters);
			}
			else {
				$gridcontroller->reloadgrid($parameters['oldgrid']);
				$this->response->assign('gridextra_' . $parameters['oldgrid'],'innerHTML','');
			}

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

	public function wikiaddArticle($parameters = array()){
		$view = new ui($this);

		$closerequest = new ajaxrequest('myarticle','followwikibreadcrumb',array('id' => $parameters['id'], 'section' => $parameters['section']));
		$view->assign('closerequest',$closerequest);

		$form = new form($parameters);

		$form->addField(new textField('title','Titel',$parameters['title'],array('required')));
		$form->addField(new hiddenField('alias',$parameters['alias']));
		$form->addField(new hiddenField('start',time()));
		$form->addField(new hiddenField('limit',''));
		$form->addField(new hiddenField('stop',-1));
		$form->addField(new rteField('content','Inhoud','',array('required')));
		$form->addField(new hiddenField('state','Actief'));

		$form->addField(new hiddenField('section',$parameters['section']));
		$form->addField(new hiddenField('id',$parameters['id']));

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

				$articlemodel->save($newarticle);

				$newversion->setArticleid($newarticle->getId());
				$versionmodel->save($newversion);

				$linkmodel = new myarticlesectionlinkModel();
				$newlink = new myarticlesectionlinkObject();
				$newlink->setArticleid($newarticle->getId());
				$newlink->setSectionid($parameters['section']);
				$newlink->setOrder($linkmodel->getmax('order',array('sectionid' => array('mode' => '=', 'value' => $parameters['section']))) + 1);

				$linkmodel->save($newlink);
			}
			catch (Exception $e){
				$flash = new popupController();
				$flash->createflash(array('name' => 'erroredit','type'=> 'error','content' => 'De gegevens werden niet toegevoegd! Raadpleeg de informaticadienst.'));
				return false;
			}

			$flash = new popupController();
			$flash->createflash(array('name' => 'flash' ,'type' => 'success', 'content' => 'De gegevens zijn goed toegevoegd.'));

			$this->followwikilink(array('name' =>$newarticle->getAlias(),'section' => $parameters['section'], 'id' => $parameters['id']));

			return true;
		}
		elseif(!$form->isSent()){
			$view->assign('form',$form);
			$this->response->assign('wiki_' . $parameters['section'], 'innerHTML', $view->fetch('myarticle_wiki_addarticle.tpl'));
		}
		else {
			return false;
		}

	}

	public function wikieditarticle($parameters = array()){
		$view = new ui($this);

		if(!$parameters['history']){
			$this->response->addWaypoint('myarticle','wikieditarticle','wiki_' . $parameters['section'], $parameters);
		}

		$articlemodel = new myarticleModel();
		$versionmodel = new myarticleversionModel();

		$article = $articlemodel->getfromId($parameters['id']);

		$closerequest = new ajaxrequest('myarticle','followwikibreadcrumb',array('id' => $parameters['id'],'section' => $parameters['section']));
		$view->assign('closerequest',$closerequest);

		$section = new myarticlesectionObject();
		$section->setId($parameters['section']);

		if(count($article) == 1){
			$article = $article[0];

			$view->assign('article',$article);

			$aliasform = new form($parameters);

			$aliasform->addField(new textField('alias','Werktitel',$article->getAlias(),array('required')));
			$aliasform->addField(new hiddenField('id',$parameters['id']));

			$aliasform->addField(new hiddenField('section',$parameters['section']));


			if($aliasform->validate()){
				$article->setAlias($aliasform->getFieldvalue('alias'));

				$flash = new popupController();

				try {
					$articlemodel->save($article);
				}
				catch(Exception $e){
					$flash->createflash(array('name' => 'error','type' => 'error', 'content' => 'De aanpassing werd niet doorgevoerd! Contacteer de informaticadienst.'));

				}


				$flash->createflash(array('name' => 'error','type' => 'success', 'content' => 'De aanpassing werd goed doorgevoerd.'));

				$this->followwikibreadcrumb(array('id' => $parameters['id'],'section' => $parameters['section']));
				return true;
			}
			elseif(!$aliasform->isSent()){
				$view->assign('aliasform',$aliasform);
			}


			$versionsids = $article->getVersion();

			$grid = new mygrid('articleversions-' . $article->getId());
			$grid->setModel(new myarticleversionModel());

			$idcond = array('articleid' => array('mode' => '=','value'=>$parameters['id']));


			$grid->setDefaultconditions($idcond);
			$grid->setDefaultorder(array('fields' => array('state', 'creationdate'), 'type' => 'DESC'));

			$grid->registerEditrequest('myarticle','wikieditversion',array('id' => '{id}','articleid' => $parameters['id'] ,'title' => 'Versie aanpassen', 'myacl' => array('target' => $section,'right' => 'manage_articlelinks','default' => false)));

			$view->assign('grid',$grid);


			$this->response->assign('wiki_' . $parameters['section'],'innerHTML',$view->fetch('myarticle_wiki_editarticle.tpl'));
		}
	}

	public function deleteArticle($parameters = array()){
		if($parameters['sure'] == 'sure'){
			$model = new myarticleModel();
			$flash = new popupController();

			try{
				$model->deletebyId($parameters['id']);
			}
			catch(Exception $e){
				$flash->createflash(array('name' => 'error','type' => 'error', 'content' => 'De aanpassing werd niet doorgevoerd! Contacteer de informaticadienst.'));
				return false;
			}

			$flash->createflash(array('name' => 'error','type' => 'success', 'content' => 'De aanpassing werd goed doorgevoerd.'));
			return true;
		}
		else {
			$model = new myarticleModel();
			$article = $model->getfromId($parameters['id']);
			if(count($article) == 1){
				$article = $article[0];
				$view = new ui($this);

				$view->assign('article',$article);

				$sectionmodel = new myarticlesectionModel();

				$linked = '';
				if(count($article->getSection()) > 0){
					$cond = array('id' => array('mode' => 'IN' , 'value' => $article->getSection()));
					$linked = $sectionmodel->get($cond);
				}

				$view->assign('linked',$linked);

				return $view->fetch('myarticle_deletearticle.tpl');
			}
		}
	}

	public function editArticle($parameters = array()){
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

			$grid->registerEditrequest('myarticle','editversion',array('id' => '{id}','articleid' => $parameters['id'] ,'title' => 'Versie aanpassen', 'myacl' => array('target' => $article,'right' => 'create_newversion','default' => false)));

			$view->assign('grid',$grid);

			if(myacl::isAllowed(myauth::getCurrentuser(),$article,'manage_sectionlinks')){

				$sectiongrid = new mygrid('sections_' . $article->getId());
				$sectiongrid->setModel(new processedmyarticlesectionlinkModel());
				$sectiongrid->setDefaultconditions($idcond);

				$sectiongrid->registerAddrequest('myarticle','addsectionlink',array('articleid' => $article->getId(), 'title' => 'Nieuwe sectie linken'));
				$sectiongrid->registerDeleterequest('myarticle','deletesectionlink',array('id' => '{id}','title' => 'Link met sectie verwijderen'));

				$view->assign('sectiongrid',$sectiongrid);
			}

			$aclcontroller = new myaclController();
			$view->assign('acllist',$aclcontroller->listacl(array('targetoutput' => '_return_','objecttype' => 'myarticleObject','objectid'=> $parameters['id'])));

			return $view->fetch('myarticle_editarticle.tpl');
		}
		else {
			return false;
		}
	}

	public function deletesectionlink($parameters = array()){
		if($parameters['sure'] == 'sure'){
			$model = new myarticlesectionlinkModel();
			$flash = new popupController();

			$curlink = $model->getfromId($parameters['id']);
			if(count($curlink) == 1){


				try {

					$model->deletebyId($parameters['id']);
				}
				catch(Exception $e){
					$flash->createflash(array('name' => 'error','type' => 'error', 'content' => 'De aanpassing werd niet doorgevoerd! Contacteer de informaticadienst.'));
					return false;
				}
			}
			else {
				$flash->createflash(array('name' => 'warning','type' => 'warning', 'content' => 'De aanpassing werd niet doorgevoerd omdat deze link reeds verwijderd was!'));
				return false;
			}

			$flash->createflash(array('name' => 'error','type' => 'success', 'content' => 'De aanpassing werd goed doorgevoerd.'));
			return true;
		}
		else {
			$model = new processedmyarticlesectionlinkModel();
			$link = $model->getfromId($parameters['id']);
			if(count($link) == 1){
				$view = new ui($this);

				$view->assign('link',$link[0]);

				return $view->fetch('myarticle_deletesectionlink.tpl');
			}
		}
	}

	public function addsectionlink($parameters = array()){

		if(isset($parameters['sectionid'])){
			$newlink = new myarticlesectionlinkObject();
			$newlink->setArticleid($parameters['articleid']);
			$newlink->setSectionid($parameters['sectionid']);

			$model = new myarticlesectionlinkModel();

			$newlink->setOrder($model->getmax('order',array('sectionid' => array('mode' => '=', 'value' => $parameters['sectionid']))) + 1);

			$flash = new popupController();

			$testcond = array('AND' => array(array('sectionid' => array('mode' => '=', 'value' => $parameters['sectionid'])), array('articleid' => array('mode' => '=','value' => $parameters['articleid']))));
			if(count($model->get($testcond)) > 0){
				$flash->createflash(array('name' => 'warning','type' => 'warning', 'content' => 'Deze sectie was reeds gelinked'));
				return false;
			}

			try {
				$model->save($newlink);
			}
			catch (Exception $e){
				$flash->createflash(array('name' => 'error','type' => 'error', 'content' => 'De aanpassing werd niet doorgevoerd! Contacteer de informaticadienst.'));
				return false;
			}

			$flash->createflash(array('name' => 'error','type' => 'success', 'content' => 'De aanpassing werd goed doorgevoerd.'));

			$gridcontr = new mygridController();
			$gridcontr->reloadgrid($parameters['oldgrid']);

			return true;
		}
		else {
			$view = new ui($this);

			$grid = new mygrid('sections');
			$grid->setModel(new myarticlesectionModel());



			$grid->registerRequest('name','myarticle','addsectionlink',array('sectionid' => '{id}','articleid' => $parameters['articleid'], 'oldgrid' => $parameters['-gridid-'], 'myacl' => array('target' => '{this}', 'right' => 'manage_articlelinks', 'default' => false)));

			$view->assign('grid',$grid);

			return $view->fetch('myarticle_addsectionlink.tpl');
		}
	}

	public function editversion($parameters = array()){
		$view = new ui($this);

		$articlemodel = new myarticleModel();
		$versionmodel = new myarticleversionModel();

		$version = $versionmodel->getfromId($parameters['id']);

		if(count($version) == 1){
			$version = $version[0];
			$form = new mygridform($parameters,$parameters['-gridid-'],'edit');

			$form->addField(new textField('title','Titel',$version->getTitle(),array('required')));
			$form->addField(new datepickerField('start','Gepubliceerd van',true,$version->getStartpublishdate(),array('required')));
			if($version->getStoppublishdate() == -1){
				$form->addField(new checkboxField('limit','Publicatie gelimiteerd in tijd','limit',false));
				$form->addField(new datepickerField('stop','Gepubliceerd tot',true,'',array('required')));
			}
			else {
				$form->addField(new checkboxField('limit','Publicatie gelimiteerd in tijd','limit',true));
				$form->addField(new datepickerField('stop','Gepubliceerd tot',true,$version->getStoppublishdate(),array('required')));
			}
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

	public function wikieditversion($parameters = array()){
		$view = new ui($this);

		$articlemodel = new myarticleModel();
		$versionmodel = new myarticleversionModel();

		$version = $versionmodel->getfromId($parameters['id']);

		if(count($version) == 1){
			$version = $version[0];
			$form = new mygridform($parameters,$parameters['-gridid-'],'edit');

			$form->addField(new textField('title','Titel',$version->getTitle(),array('required')));
			$form->addField(new hiddenField('start',time()));
			$form->addField(new hiddenField('alias',$parameters['alias']));

			$form->addField(new hiddenField('limit',''));
			$form->addField(new hiddenField('stop',-1));

			$form->addField(new hiddenField('section',$parameters['section']));

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
}
?>
