<?php
class keukenpatientModel extends mssqlmodel {
	protected $datastore = 'keukenpakket';
	protected $table = 'app_patient';

	protected $mapping = array(
		'id' => 'id',
		'voornaam' => 'voornaam',
		'achternaam' => 'achternaam',
		'currentdossiernr' => 'currentdossiernr',
		'patientnr' => 'patientnr',
		'geboortedatum' => 'geboortedatum',
		'geslacht' => 'geslacht',
		'kamer' => 'kamer',
		'bed' => 'bed',
		'campus' => 'campus',
		'verplaatsing' => 'verplaatsing',
		'dokterognummer' => 'dokterognummer',
		'dokternaam' => 'dokternaam',
		've' => 've'
	);


}
?>