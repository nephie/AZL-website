<?php
class prspatientModel extends mssqlmodel {
	protected $datastore = 'prs';
	protected $table = 'v_AZL_bed';
	
	protected $mapping = array(
		'nr_ve' => 'nr_ve',
		'nr_kamer' => 'nr_kamer',
		'nr_loc_bed' => 'nr_loc_bed',
		'verplaatsing' => 'verplaatsing',
		'nr_dos' => 'nr_dos',
		'nr_pat' => 'nr_pat',
		'fnaam_pat' => 'fnaam_pat',
		'vnaam_pat' => 'vnaam_pat',
		'dat_geb' => 'dat_geb',
		'geslacht' => 'geslacht',
		'nr_dok' => 'nr_dok',
		'fnaam_dok' => 'fnaam_dok',
		'vnaam_dok' => 'vnaam_dok'
	);
	
	protected function fillObject($data,$noassoc = false){
		$object = parent::fillObject($data,$noassoc);
		
		$object->setFnaam_pat(iconv("ISO-8859-15","UTF-8",$object->getFnaam_pat()));
		$object->setVnaam_pat(iconv("ISO-8859-15","UTF-8",$object->getVnaam_pat()));
		$object->setFnaam_dok(iconv("ISO-8859-15","UTF-8",$object->getFnaam_dok()));
		$object->setVnaam_dok(iconv("ISO-8859-15","UTF-8",$object->getVnaam_dok()));
		
		return $object;
	}
}
?>