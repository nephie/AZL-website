<?php

class dirstatusModel extends mssqlmodel {
	protected $table = 'app_status';
	protected $datastore = 'directorywatcher';
	protected $mapping = array(
								'id' => 'id',
								'path' => 'path',
								'parent' => 'parent',
								'exists' => 'exists',
								'subdirs' => 'subdirs',
								'numfiles' => 'numfiles',
								'lastfiletime' => 'lastfiletime',
								'oldestfiletime' => 'oldestfiletime',
								'reporttime' => 'reporttime',
								'status' => 'status'
							);
}
?>