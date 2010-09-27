<?php

	abstract class mssqlmodel extends model {


		protected $table;
		protected $table_prefix = 'app_';

		/**
		 * start the sql transaction
		 *
		 * @return boolean
		 */
		protected function startTransaction(){
			if(!isset(self::$transactionStarted[$this->datastore]) || self::$transactionStarted[$this->datastore] == false){
				/*
				odbc_exec('BEGIN TRANSACTION' , $this->con);
				if (odbc_errormsg($this->con)){
					throw new Exception('unable to start transaction: '. odbc_errormsg($this->con));
				}
				*/
				self::$transactionStarted[$this->datastore] = true;

				return true;
			}

			return false;
		}

		/**
		 * commit the sql transaction
		 *
		 */
		protected function commitTransaction(){
			if(self::$transactionStarted[$this->datastore] == true){
				if(!odbc_commit($this->con)){
					throw new Exception('unable to commit transaction: '. odbc_errormsg($this->con));
				}
				self::$transactionStarted[$this->datastore] = false;
			}
		}

		/**
		 * rollback the sql transaction
		 *
		 */
		protected function rollbackTransaction(){
			if(self::$transactionStarted[$this->datastore] == true){
				if(!odbc_rollback($this->con)){
					throw new Exception('unable to rollback transaction: '. odbc_errormsg($this->con));
				}
				self::$transactionStarted[$this->datastore] = false;
			}
		}

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
			if(!isset(self::$connection[$this->datastore])){

				self::$connection[$this->datastore] = odbc_connect('DRIVER={SQL Server};SERVER=' . $config['host'] . ';DATABASE=' . $config['db'] . '' , $config['user'], $config['password']);


				if(!self::$connection[$this->datastore]){
					throw new connectException('Could not connect to the datastore: ' . odbc_errormsg());
				}

				if(!odbc_autocommit(self::$connection[$this->datastore] , false)){
					throw new connectException('unable to connect: '. odbc_errormsg());
				}
			}

			$this->con = &self::$connection[$this->datastore];
		}

		/**
		 * Return an array that corresponds to the fetched row transformed into an object by the call of fillObject
		 *
		 *
		 * @param result_set $result is het resultaat dat wordt veranderd met het methode fillObject
		 * @return array
		 * @throws classException
		 */
		protected function parseResult($result){
			$output = array();
			try{
				while($data = odbc_fetch_array($result)){
					$output[] = $this->fillObject($data);
				}
			}catch (classException $e){
				throw $e;
			}
			return $output;
		}

		/**
		 * Escapes special characters in the string before sending a query to odbc
		 *
		 *
		 * @param mixed $string  is de string die moet veranderd worden in een SQL string.
		 * Vandaar dat de wildcard * verandert in % en de woorden worden geplaatst tussen aanhaling tekens �.
		 *
		 * @return string

		 */
		public function parseString($string){
			if(!is_numeric($string)){
				$string = str_replace('*' , '%' , $string);

				$string = ereg_replace("'","''",$string);

				$string = "'" . $string . "'";
			}
			return $string;
		}

		public function getcount($conditions = array()){

			$sql = 'SELECT count(*) as count FROM [' . $this->table . '] ';

			$clause = $this->getClause($conditions);
	        if( $clause != ''){
	            $sql .= 'WHERE ' . $clause;
	        }

	        $result = odbc_exec($this->con , $sql);
	        if ($result === FALSE){
	        	throw new searchException('unable to conduct the search: ' . odbc_errormsg($this->con) .' / ' . $sql);
	        }

	        $row = odbc_fetch_array($result);
	        $count = $row['count'];

	        return $count;
		}

		public function getmax($attr , $conditions = array(), $amount=0 , $offset =0){
			$revmap = array_flip($this->mapping);
			$dbattr = $revmap[$attr];
			$sql = 'SELECT max('.$dbattr.') as max FROM [' . $this->table . '] ';


		//WHERE
			$clause = $this->getClause($conditions);
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

	        $result = odbc_exec($this->con , $sql);
	        if ($result === FALSE){
	        	throw new searchException('unable to conduct the search: ' . odbc_errormsg($this->con) );
	        }

	        $row = odbc_fetch_array($result);
	        $max = $row['max'];

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
		public function get($conditions = array() ,$order = '',  $amount=0 , $offset =0){
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

			$what = '[' . implode('] , [' , array_keys($this->mapping)) . ']';
			$table = 'FROM [' . $this->table . ']';
			$clause = $this->getClause($conditions);

			if($amount != 0 && $offset == 0){

				$sql = 'SELECT TOP ' . $amount . ' '  . $what . ' ' . $table;

				if($clause != ''){
					$sql .= ' WHERE ' . $clause;

				}
				if($order != ''){
					$orderby = $this->getOrder($order);
					$sql .= ' ORDER BY ' . $orderby['what'] . ' ' . $orderby['direction'];
				}
			}
			elseif( $amount != 0 && $offset != 0) {

				if($order == ''){
					$order = array('fields' => array($this->id), 'type' => 'ASC');
				}

				$sql = 'SELECT * FROM (
							SELECT TOP ' . $amount . ' * FROM (
				';

				$sql .= 'SELECT TOP ' . ($amount + $offset) . ' '  . $what . ' ' . $table;

				if($clause != ''){
					$sql .= ' WHERE ' . $clause;

				}
				if($order != ''){
					$orderby = $this->getOrder($order);
					$sql .= ' ORDER BY ' . $orderby['what'] . ' ' . $orderby['direction'];
				}
				$reverse = (strtoupper($orderby['direction']) == 'ASC')? 'DESC' : 'ASC';
				$sql .= ') AS newtbl1 ORDER BY ' . $orderby['what'] . ' ' . $reverse .'
						) AS nwetbl2 ORDER BY ' . $orderby['what'] . ' ' . $orderby['direction'];
			}
			else {
				$sql = 'SELECT ' . $what . ' ' . $table;

				if($clause != ''){
					$sql .= ' WHERE ' . $clause;

				}
				if($order != ''){
					$orderby = $this->getOrder($order);
					$sql .= ' ORDER BY ' . $orderby['what'] . ' ' . $orderby['direction'];
				}
			}


//
//	        $sql = 'SELECT  [' . implode('] , [' , array_keys($this->mapping)) . '] FROM [' . $this->table . '] ';
//		//WHERE
//			$clause = $this->getClause($conditions);
//	        if( $clause != ''){
//	            $sql .= 'WHERE ' . $clause;
//	        }
//
//		//ORDER BY
//	        if ($order != '')
//	        {
//	        	$orderby = $this->getOrder($order);
//	        	$sql .= ' ORDER BY ' . $orderby;
//	        }
//
//		//LIMIT
//	        if($offset != 0 && $amount != 0)
//	        {
//	        	$sql .= ' LIMIT ' . $offset . ' , ' . $amount;
//	        }
//	        else if($offset == 0 && $amount != 0)
//	        {
//	        	$sql .= ' LIMIT ' . $amount;
//	        }


	        $result = odbc_exec($this->con , $sql);

	        if ($result === FALSE)
	        	throw new searchException('unable to conduct the search: ' . odbc_errormsg($this->con) );

	        try{
	        	$output = $this->parseResult($result);
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
	    protected function getClause($conditions){
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
	                if(isset($this->assoc[$key])){
	                	$assocModel = new $this->assoc[$key]['joinmodel']();

	                	$cond = array_merge(array($this->assoc[$key][assocforeignkey] => $condition) , $this->assoc[$key]['condtion']);
						$assocresults = $assocModel->get($cond);

						$inArray = array();
						$function = 'get' . ucfirst($this->assoc[$key]['foreignkey']);
						foreach ($assocresults as $assocResult){
							$inArray[] = $assocResult->$function();
						}

						$in = implode(" , " , $inArray);
						$clause .= '[' . $this->table  . '].[' . $this->assoc[$key]['relationkey'] . '] ' . 'IN' . ' ( ' . $in . ' )';
	                }
	                elseif(strtoupper($condition['mode']) == 'IN' || strtoupper($condition['mode']) == 'NOT IN'){
	                	$values = array();
	                	foreach ($condition['value'] as $value){
	                		$values[] = $this->parseString($value);
	                	}
	                	$in = implode(" , " , $values);

	                	$clause .= '[' . $this->table  . '].[' . $map[$key] . '] ' . $condition['mode'] . ' ( ' . $in . ' )';
	                }
	                elseif (strtoupper($condition['mode']) == 'BETWEEN' || strtoupper($condition['mode']) == 'NOT BETWEEN'){
	                	$between = $this->parseString($condition['value']) . ' AND ' . $this->parseString($condition['topvalue']);
	                	$clause .= '[' . $this->table  . '].[' . $map[$key] . '] ' . $condition['mode'] . ' ' . $between . ' ';
	                }
	                elseif (strtoupper($condition['mode']) == 'IS NULL' || strtoupper($condition['mode']) == 'IS NOT NULL')
	                {
	                	$clause .= '[' . $this->table  . '].[' . $map[$key] . '] ' . $condition['mode'];
	                }
	                elseif($condition['mode'] == '='){
	                	$clause .= '[' . $this->table  . '].[' . $map[$key] . '] ' . 'LIKE' . ' ' . $this->parseString($condition['value']);
	                }
	                else {
	                	$clause .= '[' . $this->table  . '].[' . $map[$key] . '] ' . $condition['mode'] . ' ' . $this->parseString($condition['value']);
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
	    	foreach ($orderbys as $orderby)
	    	{
	    		$order .= '[' . $orderby . '] ,';
	    	}
	    	$order = substr($order,0,-1);

	    	$result['what'] = $order;
	    	$result['direction'] = $conditions['type'];

	    	return $result;
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
	    	try {
	    		$commit = $this->startTransaction();
	    	}
			catch (Exception $e){
				throw new deleteException('unable to delete object: '. $e->getMessage());
			}

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
				$this->rollbackTransaction();
				throw $e;
			}

	    	$clause = $this->getClause($conditions);


	    //the DELETE
	    	$sql = 'DELETE FROM [' . $this->table . '] ';

		//WHERE
	        if( $clause != '')
	            $sql .= 'WHERE ' . $clause;

	        $result = odbc_exec($this->con , $sql);
	        if ($result === FALSE){
	    		try{
	    			$this->rollbackTransaction();
	    		}
	    		catch (Exception $e){}
	        	throw new deleteException(odbc_errormsg($this->con));
	        }
	        elseif($commit){
	        	try{
	        		$this->commitTransaction();
	        	}
	        	catch (Exception $e){
	        		throw new deleteException($e->getMessage());
	        	}
	        }
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

			try {
	    		$commit = $this->startTransaction();
	    	}
			catch (Exception $e){
				throw new saveException('unable to save object: '. $e->getMessage());
			}

	    	try {
	    		$map = $this->mapping;
	    		unset($map[$this->id]);
	    		if($object->getId() == ''){
		    		$sql = 'INSERT INTO  [' . $this->table . ']( [' . implode('] , [' , array_keys($map)) . '] ) VALUES (';

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
			    	$sql = 'UPDATE [' . $this->table . '] SET ';

			    	$set = array();

					foreach ($map as $dbAttribute => $dataAttribute)
					{
						$function = 'get' . ucfirst($dataAttribute);
						try{
							$value = $object->$function();
						}catch (Exception $e){
							throw new saveException($e->getMessage());
						}
						array_push($set, ' ['.$dbAttribute. '] = ' . $this->parseString($value) . '');
					}
					$sql .= implode(', ' , $set);
					$getidfunction = 'get'.ucfirst($this->id);
					$sql .= ' WHERE [' . $this->id . '] = ' . $this->parseString($object->$getidfunction());
	    		}

		    	$result = odbc_exec($this->con , $sql);
		    	if ($result === FALSE)
		        	throw new saveException('unable to insert the object: ' . odbc_errormsg($this->con) );

		        try{

		        	$function = 'set'.ucfirst($this->id);
		        	$gfunction = 'get'.ucfirst($this->id);
		        	if($object->$gfunction() == ''){
		        		$query = "SELECT @@IDENTITY AS ID;";
						$result = odbc_exec($this->con, $query);
						$row = odbc_fetch_object($result);

		        		$object->$function($row->ID);
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
	    		try{
	    			$this->rollbackTransaction();
	    		}
	    		catch (Exception $e){}
				throw new saveException($e->getMessage());
			}


			if($commit){
				try{
	    			$this->commitTransaction();
	    		}
	    		catch (Exception $e){
	    			throw new saveException('Unable to save object: ' .$e->getMessage());
	    		}
			}
	    }

	}

?>