<?php

class ingeschrevenModel extends mysqlmodel {
	protected $mapping = array(
								'id' => 'id' , 
								'voornaam' => 'voornaam' , 
								'achternaam' => 'achternaam' , 
								'aantal' => 'aantal' ,
								'woonplaats' => 'woonplaats', 
								'mailaddress' => 'mailaddress' , 
								'registrationtime' => 'registrationtime' , 
								'ipaddress' => 'ipaddress' , 
								'userid' => 'userid' , 
								'uurid' => 'uurid' 
							);
}
?>