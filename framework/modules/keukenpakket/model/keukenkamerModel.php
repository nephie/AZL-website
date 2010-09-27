<?php
class keukenkamerModel extends mssqlmodel {
	protected $datastore = 'keukenpakket';
	protected $table = 'app_kamer';

	protected $mapping = array(
		'id' => 'id',
		'dienstid' => 'dienstid',
		'kamernr' => 'kamernr',
	);
}
?>