<?php

class mblogModel extends mssqlmodel
{
	protected $datastore = 'medibridge';
	protected $table_prefix = '';
	protected $table = 'log';
	
	protected $mapping = array(
		'id' => 'id',
		'filename' => 'filename',
		'statusdelivery' => 'statusdelivery',
		'messagedelivery' => 'messagedelivery',
		'relativesourcepath' => 'relativesourcepath',
		'sender' => 'sender',
		'reciever' => 'reciever',
		'parsedate' => 'parsedate',
		'relativedestinationpath' => 'relativedestinationpath',
		'relativebackuppath' => 'relativebackuppath',
		'relativeerrorpath' => 'relativeerrorpath',
		'statusbackup' => 'statusbackup',
		'messagebackup' => 'messagebackup',
		'statuserror' => 'statuserror',
		'messageerror' => 'messageerror'
	);
}

?>