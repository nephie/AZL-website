<?php
class keukendienstModel extends mssqlmodel {
	protected $datastore = 'keukenpakket';
	protected $table = 'app_dienst';

	protected $mapping = array(
		'id' => 'id',
		'name' => 'name',
		'dienstnr' => 'dienstnr',
		'aantalbedden' => 'aantalbedden'
	);
}
?>