<?php

class mealoptionObject extends object {

	protected $id;
	protected $name;
	protected $price;
	protected $price2;

	protected $optionsetid;
	protected $optionsetid2;

	public function getPrice(){
		return sprintf("%.2f",$this->price);
	}

	public function getPrice2(){
		return sprintf("%.2f",$this->price2);
	}
}

?>