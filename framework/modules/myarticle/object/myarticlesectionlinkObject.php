<?php
	class myarticlesectionlinkObject extends object {
		protected $id;

		protected $articleid;
		protected $sectionid;

		protected $order;

		public function getMyaclrelated(){
			return array('type' => 'articleObject','id' => $this->articleid);
		}
	}
?>