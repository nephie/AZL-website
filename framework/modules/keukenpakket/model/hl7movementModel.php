<?php
class hl7movementModel extends mssqlmodel {
	protected $datastore = 'keukenpakket';

	protected $mapping = array(
		'id' => 'id',
		'type' => 'type',
		'time' => 'time',
		'patientnr' => 'patientnr',
		'dossiernr' => 'dossiernr',
		'voornaam' => 'voornaam',
		'achternaam' => 'achternaam',
		'kamer' => 'kamer',
		'bed' => 'bed',
		'campus' => 'campus',
		'pkamer' => 'pkamer',
		'pbed' => 'pbed',
		'pcampus' => 'pcampus'
	);
}
?>