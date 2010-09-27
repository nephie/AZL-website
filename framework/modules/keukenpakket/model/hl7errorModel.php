<?php
class hl7errorModel extends mssqlmodel {
	protected $datastore = 'keukenpakket';

	protected $mapping = array(
		'id' => 'id',
		'file' => 'file',
		'error' => 'error',
	);
}
?>