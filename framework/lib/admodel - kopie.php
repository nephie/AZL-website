<?php

	abstract class admodel extends model {

		protected $datastore = 'ad';
		protected $datastore2 = 'adocmw';
		protected $dn;
		protected $domain;

		protected $attributes;
		protected $extrafilter;

		protected $con2;
		protected $dn2;

		/**
		 * Connection to the database
		 *
		 */
		public function __construct(){



			$this->domain = $domain;

			parent::__construct();

	       	require(FRAMEWORK . DS . 'conf' . DS . 'datastore.php');

			$config = $datastore[$this->datastore];
			if(!isset(self::$connection[$this->datastore])){

				self::$connection[$this->datastore] = @ldap_connect( $config['protocol'] . $config['domain']   );

				if(!self::$connection[$this->datastore]){
					throw new connectException('Could not connect to the Active Directory.');
				}

				ldap_set_option(self::$connection[$this->datastore] , LDAP_OPT_REFERRALS , 0 );
				ldap_set_option(self::$connection[$this->datastore] , LDAP_OPT_PROTOCOL_VERSION , 3 );

				if (!@ldap_bind( self::$connection[$this->datastore] , $config['user'] . '@' . $config['domain'] , $config['password'] )){
					throw new connectException('Could not bind to the Active Directory.');
				}
			}

			$this->con = &self::$connection[$this->datastore];
			$this->dn = $config['dn'];

			$config2 = $datastore[$this->datastore2];
			if(!isset(self::$connection[$this->datastore2])){

				self::$connection[$this->datastore2] = @ldap_connect( $config2['protocol'] . $config2['domain']   );

				if(!self::$connection[$this->datastore2]){
					throw new connectException('Could not connect to the Active Directory.');
				}

				ldap_set_option(self::$connection[$this->datastore2] , LDAP_OPT_REFERRALS , 0 );
				ldap_set_option(self::$connection[$this->datastore2] , LDAP_OPT_PROTOCOL_VERSION , 3 );

				if (!@ldap_bind( self::$connection[$this->datastore2] , $config2['user'] . '@' . $config2['domain'] , $config2['password'] )){
					throw new connectException('Could not bind to the Active Directory.');
				}
			}

			$this->con2 = &self::$connection[$this->datastore2];
			$this->dn2 = $config2['dn'];

			$this->attributes = array_keys($this->mapping);
		}

		/**
		 * Escapes special characters in the string before sending a query
		 *
		 *
		 * @param mixed $string  is de string die moet veranderd worden in een SQL string.
		 * Vandaar dat de wildcard * verandert in % en de woorden worden geplaatst tussen aanhaling tekens �.
		 *
		 * @return string

		 */
		public function parseString($string){
			return $string;
		}

		public function adescape($str,$for_dn = false){
			if  ($for_dn)
		        $metaChars = array(',','=', '+', '<','>',';', '\\', '"', '#', '(', ')');
		    else
		        $metaChars = array('*', '(', ')', '\\', chr(0));

		    $quotedMetaChars = array();
		    foreach ($metaChars as $key => $value) $quotedMetaChars[$key] = '\\'.str_pad(dechex(ord($value)), 2, '0');
		    $str=str_replace($metaChars,$quotedMetaChars,$str); //replace them

		    return ($str);
		}

		public function parseidforsearch($id){
			$newid = '';
			for($i = 0; $i < 16; $i++){
				$newid .= '\\' . substr($id,$i * 2,2);
			}

			return $newid;
		}

	/**
		 * Return an array that corresponds to the fetched row transformed into an object by the call of fillObject
		 *
		 *
		 * @param result_set $result is het resultaat dat wordt veranderd met het methode fillObject
		 * @return array
		 * @throws classException
		 */
		protected function parseResult($result,$noassoc = false){
			$output = array();

			if( ldap_count_entries($this->con , $result ) )
			{
			    ldap_sort($this->con , $result , 'samaccountname');
				$i=0;
				$entry = ldap_first_entry($this->con, $result);
				do {
					$attributes = ldap_get_attributes($this->con, $entry);
					for($j=0; $j<$attributes['count']; $j++)
					{
						$values = ldap_get_values_len($this->con, $entry,$attributes[$j]);

						$rows[$i][strtolower( $attributes[$j] )] = $values;



						if( strtolower( $attributes[ $j ] ) == 'objectguid' )
						{
							$rows[ $i ][ strtolower( $attributes[ $j ] ) ][ 0 ] = bin2hex( $values[ 0 ] );
						}

						if(count($rows[$i][strtolower( $attributes[$j] )]) == 2){
							$rows[$i][strtolower( $attributes[$j] )] = $rows[$i][strtolower( $attributes[$j] )][0];
						}
						else{
							unset($rows[$i][strtolower( $attributes[$j] )]['count']);
						}
					}
					$i++;
				}
				while ($entry = ldap_next_entry($this->con, $entry));

				$rows[ 'count' ] = ldap_count_entries( $this->con , $result );

				for( $index = 0 ; $index < $rows[ 'count' ] ; $index++ )
				{
					$object = $this->fillObject($rows[ $index ] ,!$noassoc);
					$output[] = $object;
				}
			}

			return $output;
		}

		/**
		 * Return the query with the clauses, the order and the limit.
		 * for the repeated queries, use of the cache
		 * Deze functie bied de mogelijkheid de query tot stand te brengen met
		 * de bedoeling het nodige element op te zoeken.
		 * Zij implementeert een cache systeem om niet te veel queries te
		 * maken naar de server toe. Zij gebruikt getOrder en getClause.
		 *
		 * @param mixe $conditions de voorwaarden om het element binnen de database te onderzoeken.
		 * Dit is een array die een bepaalde structuur heeft: een mode: in, between, is null, �
		 * en een value (en topvalue indien is between)
		 * @param string $order is de volgorde binnen dewelke de gegevens moeten worden weergegeven
		 * @param int $amount laten een beperkt aantal elementen weergeven toe.
		 * @param int $offset laten een beperkt aantal elementen weergeven toe.
		 * @return array
		 * @throws searchException
		 */
		public function get($conditions = array() ,$order = '',  $amount=0 , $offset =0,$noassoc = false){
			$cacheIt = false;

			if(count($conditions) == 1){
				foreach ($conditions as $var => $condition){
					if($condition['mode'] == '=' && in_array($var , $this->cacheFields )){
						if(isset(self::$cache[get_class($this)][$var][$condition['value']])){
							return self::$cache[get_class($this)][$var][$condition['value']];
						}
						else {
							$cacheIt = true;
						}
					}
				}
			}



			$filter = array();

			$filter = '(' .  $this->getClause($conditions,$noassoc) . ')';

			if(is_array($this->extrafilter)){
				$filter = '(&' . $filter . '(' . $this->getClause($this->extrafilter , true) . '))';
			}

			$result = ldap_search( $this->con , $this->dn , $filter , $this->attributes );

			if( ldap_errno( $this->con ) )
				throw new searchException( 'Unable to conduct search: ' . ldap_error($this->con ) );

			if(ldap_count_entries($this->con,$result) == 0){
				$result = ldap_search($this->con2,$this->dn2,$filter,$this->attributes);
			}

			if( ldap_errno( $this->con2 ) )
				throw new searchException( 'Unable to conduct search: ' . ldap_error($this->con2 ) );

			try{
	        	$output = $this->parseResult($result,$noassoc);
	        }catch(Exception $e){
	        	throw new searchException('unable to conduct the search: ' . $e->getMessage());
	        }




	        if($cacheIt){
	        	foreach ($conditions as $var => $condition){
					self::$cache[get_class($this)][$var][$condition['value']] = $output;
				}
	        }
	        return $output;

	    }

		public function getmax($attr , $conditions = array(), $amount=0 , $offset =0,$noassoc = false){
			throw new Exception('unable to get max: Function not implemented');
		}

		public function getcount($conditions = array(),$noassoc = false){
			throw new Exception('unable to get count: Function not implemented');
		}

	    /**
	     * Return the clauses for the conditions given as parameter
	     *
	     * @param array $conditions de voorwaarden om het element op te zoeken binnen de database.
	     * Dit is een array dat een bepaalde structuur heeft: een mode: in, between, is null, �
	     * en een value (en topvalue indien is between). Bij default, worden de voorwaarden gelinkt met een AND.
	     * Men moet het omdraaien van het array  van de mapping  niet uit het oog verliezen
	     * om dataAttribute => dbattribute te hebben
	     *
	     * @return string
	     */
	    private function getClause($conditions , $dontmap = false,$noassoc = false){
	        $clause = '';

	        foreach ($conditions as $key => $condition){
	            if(!isset($condition['value'])){
	                if($key == '')
	                    $key = 'AND';

	                foreach ($condition as $k => $c){
	                    $out[] = $this->getClause(array($k => $c));
	                }
	                $clause .= '( &(' . implode( ' )( ' , $out) . ') ) ';
	            }
	            else  {
	                $map = array_flip($this->mapping);


	                if($dontmap){
	                	$clause .= $key . $condition['mode'] . $this->parseString($condition['value']);
	                } else {
	                	if($map[$key] == 'objectguid'){
	                		$escapedHex = '';
							for( $i = 0 ; $i < strlen( $condition['value'] ) ; $i += 2 )
							{
								$escapedHex .= '\\' . substr( $condition['value'] , $i , 2 );
							}

							$condition['value'] = $escapedHex;
	                	}
	                	$clause .= $map[$key] . $condition['mode'] . $this->parseString($condition['value']);
	                }
	            }
	        }
	        return $clause;
	    }


	    /**
	     * Delete the row in the DB for the object(s) corresponding to the conditions given as parameter
	     * the associated object are also deleted
	     *
	     * Men moet aandacht geven aan het verwijderen van de elementen die
	     * geassocieerd zijn via deletehasmany en deletehabtm.
	     * Indien een fout optreedt, moet men de hele transactie annuleren.
	     * In het andere geval, moet men die valideren (commi)
	     *
	     * @param array $conditions de voorwaarden om het element binnen de DB op te zoeken en te verwijderen.
	     * Deze variabel kan een array zijn of een object. Indien deze een object is,
	     * zullen de voorwaarden gecre�erd worden op basis van de ID.
	     * Ten slotte, heeft het array hetzelfde structuur als voor een get.
	     *
	     * @throws deleteException
	     */
	    public function delete($conditions = array()){
	    	throw new deleteException('unable to delete object: Function not implemented');
	    }

	    /**
	     * save the object in the DB and the associated object if $saveAssociations is set to true
	     *
	     * Indien de ID van het object niet bestaat, zal het object toegevoegd worden.
	     * Indien de ID bestaat, zal hij ge�pdate worden.
	     * De SQL query �ON DUPLICATE KEY� is in staat om de keuze hiertussen te maken.
	     *
	     * @param mixed $object is het object dat bewaard moet worden
	     * @throws saveException
	     */
	    public function save($object){
			throw new saveException('unable to delete object: Function not implemented');
	    }

	}

?>