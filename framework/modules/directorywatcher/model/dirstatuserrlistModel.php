<?php

class dirstatuserrlistModel extends dirstatusModel {
	protected $table = 'app_statuserrlist';
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
								'status' => 'status',
								'oldid' => 'oldid'
							);
}
?>