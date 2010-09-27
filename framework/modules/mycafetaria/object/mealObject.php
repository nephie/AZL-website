<?php

class mealObject extends object {

	protected $id;
	protected $name;
	protected $mealtypeid;
	protected $price;
	protected $price2;

	protected $optionsetid;
	protected $blackoutid;

	public function getPrice(){
		return sprintf("%.2f",$this->price);
	}

	public function getPrice2(){
		return sprintf("%.2f",$this->price2);
	}
}

?>