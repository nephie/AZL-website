<?php
require_once( FRAMEWORK . DS . 'lib' . DS . 'adodb5' . DS . 'adodb.inc.php' );

abstract class adomodel extends model {

		protected $table;
		protected $table_prefix = 'app_';


		/**
		 * Connection to the database
		 *
		 */
		public function __construct(){
			parent::__construct();

	        if($this->table == ''){
	            $this->table = $this->table_prefix . str_replace('Model' , '' , get_class($this));
    	    }

			require(FRAMEWORK . DS . 'conf' . DS . 'datastore.php');

			$config = $datastore[$this->datastore];

			$this->con = NewADOConnection($config['protocol']);
			if(!$this->con){
				throw new connectException('Could not initialize the ADO class');
			}

			if($this->con->Connect($config['host'], $config['user'], $config['password'], $config['db'])){
				$this->con->setFetchMode(ADODB_FETCH_ASSOC);
			}
			else {
				throw new connectException('Could not connect to the datastore: ' . $this->con->ErrorMsg());
			}

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
			try{
				while($data = $result->FetchRow()){
					$output[] = $this->fillObject($data,$noassoc);
				}
			}catch (classException $e){
				throw $e;
			}
			return $output;
		}

		/**
		 * Escapes special characters in the string before sending a query
		 *
		 *
		 * @param mixed $string
		 *
		 * @return string

		 */
		public function parseString($string){

				$string = str_replace('*' , '%' , $string);

				$string = $this->con->qstr($string,get_magic_quotes_gpc());

			return $string;
		}

		public function getmax($attr , $conditions = array(), $amount=0 , $offset =0, $noassoc = false){
			$revmap = array_flip($this->mapping);
			$dbattr = $revmap[$attr];
			$sql = 'SELECT max(' . $this->columnescapel .$dbattr. $this->columnescaper .') as max FROM ' . $this->columnescapel . $this->table . $this->columnescaper .' AS T1 ';


		//WHERE
			$clause = $this->getClause($conditions,$noassoc);
	        if( $clause != ''){
	            $sql .= 'WHERE ' . $clause;
	        }

		//LIMIT
	        if($offset != 0 && $amount != 0)
	        {
	        	$sql .= ' LIMIT ' . $offset . ' , ' . $amount;
	        }
	        else if($offset == 0 && $amount != 0)
	        {
	        	$sql .= ' LIMIT ' . $amount;
	        }

	        if($offset != 0 && $amount != 0)
	        {
	        	$result =& $this->con->SelectLimit($sql,$amount,$offset);
	        }
	        else if($offset == 0 && $amount != 0)
	        {
	        	$result =& $this->con->SelectLimit($sql,$amount);
	        }
	        else { // No limit

	        	$result =& $this->con->execute($sql);
	        }

	        if (!$result)
	        	throw new searchException('unable to conduct the search: ' . $this->con->ErrorMsg() );

	       	$data = $result->FetchRow();
	        $max = $data['max'];

	        return $max;
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
		public function get($conditions = array() ,$order = '',  $amount=0 , $offset =0, $noassoc = false){
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

	        $sql = 'SELECT  ' . $this->columnescapel . implode($this->columnescaper . ' , ' . $this->columnescapel , array_keys($this->mapping)) . $this->columnescaper .' FROM ' . $this->columnescapel . $this->table . $this->columnescaper .' as T1 ';
		//WHERE
			$clause = $this->getClause($conditions,$noassoc);
	        if( $clause != ''){
	            $sql .= 'WHERE ' . $clause;
	        }

		//ORDER BY
	        if ($order != '')
	        {
	        	$orderby = $this->getOrder($order);
	        	if($orderby != ''){
	        		$sql .= ' ORDER BY ' . $orderby;
	        	}
	        }

		//LIMIT
	        if($offset != 0 && $amount != 0)
	        {
	        	$result =& $this->con->SelectLimit($sql,$amount,$offset);
	        }
	        else if($offset == 0 && $amount != 0)
	        {
	        	$result =& $this->con->SelectLimit($sql,$amount);
	        }
	        else { // No limit

	        	$result = $this->con->execute($sql);
	        }

	        if (!$result){
	        	throw new searchException('unable to conduct the search: ' . $sql . '|' . $this->con->ErrorMsg() );
	        }

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
	    protected function getClause($conditions, $noassoc = false){
	        $clause = '';

	        foreach ($conditions as $key => $condition){
	            if(!isset($condition['value'])){
	                if($key == '')
	                    $key = 'AND';

	                foreach ($condition as $k => $c){
	                    $out[] = $this->getClause(array($k => $c));
	                }
	                $clause .= '( ' . implode( ' ' . strtoupper($key) . ' ' , $out) . ' ) ';
	            }
	            else  {
	                $map = array_flip($this->mapping);

	                //	Association support
	                if(isset($this->assoc[$key]) && !$noassoc){
	                	$assocModel = new $this->assoc[$key]['joinmodel']();

	                	$cond = array_merge(array($this->assoc[$key]['assocforeignkey'] => $condition) , $this->assoc[$key]['condition']);
	                	if(is_array($this->assoc[$key]['condition']) && count($this->assoc[$key]['condition']) > 0){
	                		$cond = array('AND' => array(array($this->assoc[$key]['assocforeignkey'] => $condition),$this->assoc[$key]['condition']));
	                	}
	                	else {
	                		$cond = array($this->assoc[$key]['assocforeignkey'] => $condition);
	                	}
						$assocresults = $assocModel->get($cond,'',0,0,true);

						$inArray = array();
						$function = 'get' . ucfirst($this->assoc[$key]['foreignkey']);
						foreach ($assocresults as $assocResult){
							$inArray[] = $assocResult->$function();
						}

						if(count($inArray) > 0){
							$values = array();
		                	foreach ($inArray as $value){
		                		$values[] = $this->parseString($value);
		                	}
							$in = implode(" , " , $values);
							$clause .= 'T1.' . $this->columnescapel . $this->assoc[$key]['relationkey'] . $this->columnescaper . ' ' . 'IN' . ' ( ' . $in . ' )';
						}
						else {
							//dirty as hell, but meh
							$clause .= "'true' = 'false'";
						}
	                }
	                elseif(strtoupper($condition['mode']) == 'IN' || strtoupper($condition['mode']) == 'NOT IN'){
	                	$values = array();
	                	foreach ($condition['value'] as $value){
	                		$values[] = $this->parseString($value);
	                	}
	                	$in = implode(" , " , $values);

	                	$clause .= 'T1.' . $this->columnescapel . $map[$key] . $this->columnescaper . ' ' . $condition['mode'] . ' ( ' . $in . ' )';
	                }
	                elseif (strtoupper($condition['mode']) == 'BETWEEN' || strtoupper($condition['mode']) == 'NOT BETWEEN'){
	                	$between = $this->parseString($condition['value']) . ' AND ' . $this->parseString($condition['topvalue']);
	                	$clause .= 'T1.' . $this->columnescapel . $map[$key] . $this->columnescaper . ' ' . $condition['mode'] . ' ' . $between . ' ';
	                }
	                elseif (strtoupper($condition['mode']) == 'IS NULL' || strtoupper($condition['mode']) == 'IS NOT NULL')
	                {
	                	$clause .= 'T1.' . $this->columnescapel . $map[$key] . $this->columnescaper . ' ' . $condition['mode'];
	                }
	                elseif($condition['mode'] == '='){
	                	$clause .= 'T1.' . $this->columnescapel . $map[$key] . $this->columnescaper . ' ' . 'LIKE' . ' ' . $this->parseString($condition['value']);
	                }
	                elseif(strtoupper($condition['mode']) == 'MAXVALUE'){
						$clause .= '(T1.' . $this->columnescapel . $map[$key] . $this->columnescaper . ' =
                          (SELECT MAX(T2.' . $this->columnescapel . $map[$key] . $this->columnescaper . ') AS Expr1
                            FROM ' . $this->columnescapel . $this->table . $this->columnescaper .' as T2
                            WHERE (T1.' . $this->columnescapel . $condition['value'] . $this->columnescaper . ' = T2.' . $this->columnescapel . $condition['value'] . $this->columnescaper . ')))';

	                }
	                else {
	                	$clause .= 'T1.' . $this->columnescapel . $map[$key] . $this->columnescaper . ' ' . $condition['mode'] . ' ' . $this->parseString($condition['value']);
	                }
	            }
	        }
	        return $clause;
	    }

	    /**
	     * Return the clause order by for the conditions given as parameter
	     *
	     * @param array $conditions laat toe op een correcte manier de Order By op te maken.
	     * Dit array bevat twee velden. De eerste zijnde een array van Fields en de tweede zijnde het Type: ASC of DESC.
	     *
	     * @return string
	     */
	    private function getOrder($conditions)
	    {
	    	$order ='';
	    	$orderbys = $conditions['fields'];
	    	$orderset = false;
	    	foreach ($orderbys as $orderby)
	    	{
	    		if(!in_array($orderby,$this->latesortfields)){
	    			$order .= 'T1.' . $this->columnescapel . $orderby . $this->columnescaper . ' ,';
	    			$orderset = true;
	    		}
	    	}
	    	$order = substr($order,0,-1);
	    	$order .= $conditions['type'];
	    	if($orderset){
	    		return $order;
	    	}
	    	else {
	    		return '';
	    	}
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
	    public function delete($conditions = array())
	    {
	    	$this->con->StartTrans();

			if(is_object($conditions)){
				$function = 'get' . ucfirst($this->mapping[$this->id]);
				$conditions = array($this->mapping[$this->id] => array('mode' => '=' , 'value' => $conditions->$function()));
			}

			//	dependant associations (and habtm links)
			try{
				$ObjToDel = array();
				foreach ($this->assoc as $key => $assoc){
					if($assoc['dependant']){
						if($assoc['type'] == 'habtm') {
							if(count($ObjToDel) == 0){
								$ObjToDel = $this->get($conditions);
							}

							$assocModel = new $assoc['joinmodel']();
							$relmodel = new $assoc['assocmodel']();
							$getf = 'get' . ucfirst($assoc['relationkey']);
							$f = 'get' . ucfirst($key);
							foreach ($ObjToDel as $todel){
								$reltodel = $todel->$f();

								if(count($reltodel) > 0){

									//	We have the id's we need to delete now, but don't delete it yet. Instead, delete the link first and then delete these so they can't recurse back.
									$condition = array( $assoc['foreignkey'] => array(
																			'mode' => '=',
																			'value' => $todel->$getf()
											));
							   		$condition = array_merge($condition , $assoc['condition']);
							   		$assocModel->delete($condition);

							   		//	Links are gone, now we can delete the real thing
							   		$condition = array( $assoc['assocrelationkey'] => array(
							   													'mode' => 'IN',
							   													'value' => $reltodel
							   				));
									$relmodel->delete($condition);
								}
							}
						}
						elseif ($assoc['type'] == 'hasmany'){
							if(count($ObjToDel) == 0){
								$ObjToDel = $this->get($conditions);
							}

							$assocModel = new $assoc['joinmodel']();

							$getf = 'get' . ucfirst($assoc['relationkey']);
							foreach ($ObjToDel as $obj){
								$condition = array( $assoc['foreignkey'] => array(
																			'mode' => '=',
																			'value' => $obj->$getf()
											));
							   $condition = array_merge($condition , $assoc['condition']);
							   $assocModel->delete($condition);
							}
						}
					}
					elseif($assoc['type'] == 'habtm'){
						//	Links must be deleted even if it wasn't a dependant relation
						if(count($ObjToDel) == 0){
							$ObjToDel = $this->get($conditions);
						}

						$assocModel = new $assoc['joinmodel']();

						$getf = 'get' . ucfirst($assoc['relationkey']);
						foreach ($ObjToDel as $obj){
							$condition = array( $assoc['foreignkey'] => array(
																		'mode' => '=',
																		'value' => $obj->$getf()
										));
						   $condition = array_merge($condition , $assoc['condition']);
						   $assocModel->delete($condition);
						}
					}
				}
			}
			catch (Exception $e){
				$this->con->FailTrans();
				$this->con->CompleteTrans();
				throw $e;
			}

	    	$clause = $this->getClause($conditions);


	    //the DELETE
	    	$sql = 'DELETE FROM ' . $this->columnescapel . $this->table . $this->columnescaper . ' ';

		//WHERE
	        if( $clause != '')
	        	// Hack out the T1 ref because mssql is stupid
	        	$clause = str_replace('T1.','',$clause);
	            $sql .= 'WHERE ' . $clause;

	        $result = $this->con->Execute($sql);

	        if (!$result){
	    		$this->con->FailTrans();
	    		$this->con->CompleteTrans();
	        	throw new deleteException($this->con->ErrorMsg());
	        }

	        $this->con->CompleteTrans();
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

			$this->con->StartTrans();

	    	try {
	    		$map = $this->mapping;
	    		unset($map[$this->id]);
	    		if($object->getId() == ''){
		    		$sql = 'INSERT INTO  ' . $this->columnescapel . $this->table . $this->columnescaper . '( ' . $this->columnescapel . implode($this->columnescaper . ' , ' . $this->columnescapel , array_keys($map)) . $this->columnescaper . ' ) VALUES (';

			    	$values = array();
			    	foreach ($map as $dbAttribute => $dataAttribute)
			    	{
		    			$function = 'get' . ucfirst($dataAttribute);
		    			try{
		    				if(in_array($dataAttribute,$this->arrayFields)){
		    					$v = $object->$function();
		    					$v = serialize($v);
		    				}
		    				else {
		    					$v = $object->$function();
		    				}
		    				array_push($values,$v);
		    			}catch(Exception $e){
		    				throw new saveException($e->getMessage());
		    			}
			    	}
			    	$parseValues = array();
			    	foreach ($values as $value){
		        		$parseValues[] = $this->parseString($value);
		        	}
			    	$sql .= implode(' , ' , $parseValues);
			    	$sql .=')';
	    		} else {
			    	$sql = 'UPDATE ' . $this->columnescapel . $this->table . $this->columnescaper . ' SET ';

			    	$set = array();

					foreach ($map as $dbAttribute => $dataAttribute)
					{
						$function = 'get' . ucfirst($dataAttribute);
						try{
							if(in_array($dataAttribute,$this->arrayFields)){
		    					$value = $object->$function();
		    					$value = serialize($value);
		    				}
		    				else {
		    					$value = $object->$function();
		    				}
						}catch (Exception $e){
							throw new saveException($e->getMessage());
						}
						array_push($set, ' ' . $this->columnescapel . $dbAttribute . $this->columnescaper . ' = ' . $this->parseString($value) . '');
					}
					$sql .= implode(', ' , $set);
					$getidfunction = 'get'.ucfirst($this->id);
					$sql .= ' WHERE ' . $this->columnescapel . $this->id . $this->columnescaper . ' = ' . $this->parseString($object->$getidfunction());
	    		}

				$result = $this->con->Execute($sql);

		        if (!$result)

	        		throw new searchException('unable to save: ' . $this->con->ErrorMsg() );

		        try{

		        	$function = 'set'.ucfirst($this->id);
		        	$gfunction = 'get'.ucfirst($this->id);
		        	if($object->$gfunction() == ''){
		        		$object->$function($this->con->Insert_ID());
		        	}
		        }catch (Exception $e){
		        	throw new saveException($e->getMessage());
		        }

		        //	Associations
		       	foreach ($this->assoc as $key => $assoc){
		       		$getidFunction = 'get' . ucfirst($assoc['relationkey']);
		       		$assocModel = new $assoc['joinmodel'];

		       		if($assoc['type'] == 'habtm'){
		       			// Deleting everything for this object and then rebuilding would go too fast through auto incrementing ids :/

		       			$cond = array( $assoc['foreignkey'] => array(
		       															'mode' => '=',
		       															'value' => $object->$getidFunction()
		       														)

		       						);
	       				$cond = array_merge($cond , $assoc['condition']);
						$assocObj = $assocModel->get($cond);

						$getf = 'get' . ucfirst($assoc['assocforeignkey']);
						$present = array();
						foreach ($assocObj as $obj){
							$present[] = $obj->$getf();
						}

						$f1 = 'get' . ucfirst($key);
						$todel = array_diff($present , $object->$f1());
						$tosave = array_diff($object->$f1(), $present);

						foreach ($todel as $td){
							$condition = array( 'AND' => array(
											$assoc['assocforeignkey'] => array(
														'mode' => '=',
														'value' => $td
												),
											$assoc['foreignkey'] => array(
														'mode' => '=',
														'value' => $object->$getidFunction()
												)
											));
							$assocModel->delete($condition);
						}

						$f2 = 'set' . ucfirst($assoc['foreignkey']);
						$f3 = 'set' . ucfirst($assoc['assocforeignkey']);
						$f4 = 'get' . ucfirst($assoc['relationkey']);
						foreach ($tosave as $ts){
							$tmpObject = new $assoc['class'];

							$tmpObject->$f2($object->$f4());
							$tmpObject->$f3($ts);

							$assocModel->save($tmpObject);
						}
		       		}
		       		elseif ($assoc['type'] == 'hasmany'){

		       			$f1 = 'get' . ucfirst($key);
		       			$f2 = 'set' . ucfirst($assoc['foreignkey']);
		       			$f3 = 'get' . ucfirst($assoc['relationkey']);
		       			foreach ($object->$f1() as $assocValue){
		       				$cond = array( $assoc['assocforeignkey'] => array(
		       															'mode' => '=',
		       															'value' => $assocValue
		       														)

		       						);
		       				$cond = array_merge($cond , $assoc['condition']);
							$assocObj = $assocModel->get($cond);
							foreach ($assocObj as $obj) {
								$obj->$f2($object->$f3());
								$assocModel->save($obj);
							}
		       			}
		       		}
		       	}

			}catch (Exception $e){

				$this->con->FailTrans();
				$this->con->CompleteTrans();

				throw new saveException($e->getMessage());
			}


			$this->con->CompleteTrans();
	    }

	    public function getcount($conditions = array(),$noassoc = false){
	     $sql = 'SELECT  count(*) as count FROM ' . $this->columnescapel . $this->table . $this->columnescaper .' AS T1 ';
		//WHERE
			$clause = $this->getClause($conditions,$noassoc);
	        if( $clause != ''){
	            $sql .= 'WHERE ' . $clause;
	        }

	        $result = $this->con->execute($sql);

	        if(!$result){
	        	return 0;
	        }

	        $data = $result->FetchRow();

	        return $data['count'];
	    }
}

?>