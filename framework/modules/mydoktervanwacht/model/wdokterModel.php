<?php

class wdokterModel extends mssqlmodel {
	
	protected $mapping = array(
							'id' => 'id',
							'ognummer' => 'ognummer',
							'naam' => 'naam',
							'voornaam' => 'voornaam',
							'specialisme' => 'specialisme'
						);
	
}

?>