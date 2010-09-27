<?php

class mbdokterModel extends mssqlmodel
{
	protected $datastore = 'medibridge';
	protected $table_prefix = '';
	protected $table = 'dokter';
	
	protected $mapping = array(
		'id' => 'id',
		'voornaam' => 'voornaam',
		'achternaam' => 'achternaam',
		'riziv' => 'riziv',
		'specialisatie' => 'specialisatie'
	);
}

?>