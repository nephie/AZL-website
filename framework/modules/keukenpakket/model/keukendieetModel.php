<?php
class keukendieetModel extends mssqlmodel {
	protected $datastore = 'keukenpakket';
	protected $table = 'app_dieet';

	protected $mapping = array(
		'id' => 'id',
		'longname' => 'longname',
		'shortname' => 'shortname'
	);
}
?>