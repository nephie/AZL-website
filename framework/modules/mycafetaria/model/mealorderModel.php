<?php

class mealorderModel extends mssqlmodel {

	protected $mapping = array('id'=>'id', 'userid' => 'userid' , 'mealtype' => 'mealtype', 'meal' => 'meal', 'uur'=> 'uur', 'price' => 'price', 'orderuserid' => 'orderuserid', 'printed' => 'printed', 'uurtext' => 'uurtext', 'orderuurtext' => 'orderuurtext', 'orderuur' => 'orderuur', 'user' => 'user', 'orderuser' => 'orderuser');

}
?>