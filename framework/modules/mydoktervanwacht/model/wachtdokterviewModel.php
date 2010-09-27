<?php

class wachtdokterviewModel extends mssqlmodel {
	
	protected $mapping = array(
							'id' => 'id',
							'ognummer' => 'ognummer',
							'specialisme' => 'specialisme',
							'start' => 'start',
							'stop' => 'stop',
							'naam' => 'naam',
							'voornaam' => 'voornaam'
						);
	
}

?>