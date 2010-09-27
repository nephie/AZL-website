<?php

class wachtdokterModel extends mssqlmodel {
	
	protected $mapping = array(
							'id' => 'id',
							'dokter' => 'dokter',
							'specialisme' => 'specialisme',
							'start' => 'start',
							'stop' => 'stop'
						);
	
}

?>