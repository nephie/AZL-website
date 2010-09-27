<?php

$description = 'Beheer van articles en secties';

$actions['showsection']['description'] = 'Toon alle artikels van een sectie';
$actions['showsection']['params'] =
	array(
		'section' => 'getsections'
	);
$actions['showarticle']['description'] = 'Toon een artikel';
$actions['showarticle']['params'] =
	array(
		'section' => 'getarticles'
	);

if(!class_exists('myarticleConfig',false)){
	class myarticleConfig extends getandsetLib {

		public function getsections(){
			$result = array();
			$sectionmodel = new myarticlesectionModel();

			$sections = $sectionmodel->get();

			foreach($sections as $section){
				$result[$section->getId()] = $section->getName();
			}

			return $result;
		}

		public function getarticles(){
			$result = array();
			$articlemodel = new myarticleModel();

			$articles = $articlemodel->get();

			foreach($articles as $article){
				$result[$article->getId()] = $article->getAlias();
			}

			return $result;
		}
	}
}

?>