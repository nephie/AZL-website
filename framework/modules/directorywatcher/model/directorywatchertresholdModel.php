<?php

class directorywatchertresholdModel extends mssqlmodel {

	protected $datastore = "directorywatcher";
	protected $mapping = array (
							'id' => 'id',
							'path' => 'path',
							'exists' => 'exists',
							'numfiles' => 'numfiles',
							'lastfiletime' => 'lastfiletime',
							'oldestfiletime' => 'oldestfiletime',
							'mail' => 'mail',
							'mailto' => 'mailto'
						);
}
?>