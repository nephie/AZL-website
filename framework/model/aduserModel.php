<?php

class userModel extends admodel {

	protected $mapping = array('objectguid' => 'id' , 'displayname' => 'name' , 'postalcode' => 'eid' ,'samaccountname' => 'username' , 'description' => 'description' , 'memberof' => 'memberof' , 'mail' => 'mail');

	protected $extrafilter = array( 'objectCategory' => array('mode' => '=' , 'value' => 'user'));

	public function getfromName( $name ,$noassoc = false){
		$name = $this->adescape($name);
		$filter = "(&(objectCategory=user)(objectClass=user)(samaccountname=$name))";
		$result = ldap_search( $this->con , $this->dn , $filter , $this->attributes );

		if( ldap_errno( $this->con ) )
			throw new searchException( 'Unable to conduct search: ' . ldap_error($this->con ) );

		if(ldap_count_entries($this->con,$result) == 0){
			$result = ldap_search($this->con2,$this->dn2,$filter,$this->attributes);
		}

		if( ldap_errno( $this->con2 ) )
			throw new searchException( 'Unable to conduct search: ' . ldap_error($this->con2 ) );

		return $this->parseResult($result ,$noassoc);
	}

	public function getfromDn( $dn  ,$noassoc = false){
		 $dn= $this->adescape($dn,true);
		$filter = "(&(objectCategory=user)(objectClass=user)(distinguishedname=$dn))";
		$result = ldap_search( $this->con , $this->dn , $filter , $this->attributes );

		if( ldap_errno( $this->con ) )
			throw new searchException( 'Unable to conduct search: ' . ldap_error($this->con ) );

		if(ldap_count_entries($this->con,$result) == 0){
			$result = ldap_search($this->con2,$this->dn2,$filter,$this->attributes);
		}

		if( ldap_errno( $this->con2 ) )
			throw new searchException( 'Unable to conduct search: ' . ldap_error($this->con2 ) );

		return $this->parseResult($result ,$noassoc);
	}

	public function getfromDisplayname( $fullname  ,$noassoc = false){
		$fullname = $this->adescape($fullname);
		$filter = "(&(objectCategory=user)(objectClass=user)(displayname=$fullname))";
		$result = ldap_search( $this->con , $this->dn , $filter , $this->attributes );

		if( ldap_errno( $this->con ) )
			throw new searchException( 'Unable to conduct search: ' . ldap_error($this->con ) );

		if(ldap_count_entries($this->con,$result) == 0){
			$result = ldap_search($this->con2,$this->dn2,$filter,$this->attributes);
		}

		if( ldap_errno( $this->con2 ) )
			throw new searchException( 'Unable to conduct search: ' . ldap_error($this->con2 ) );

		return $this->parseResult($result ,$noassoc);
	}


	public function searchforacl($value){
		require(FRAMEWORK . DS . 'conf' . DS . 'myacl.php');
		return array_merge($this->searchnamesforgroup($value,$baseusergroup,true),$this->searchnamesforgroup($value,$baseusergroupocmw,true));
	}


	public function searchnamesforgroup($value, $groupdn,$allowgeneric = false) {
		require(FRAMEWORK . DS . 'conf' . DS . 'mycafetaria.php');

		$value = $this->adescape($value);
		$groupdn = $this->adescape($groupdn,true);
		$agemenegebruikersdn = $this->adescape($agemenegebruikersdn,true);

		if(!$allowgeneric){
			$filter = "(&(objectCategory=user)(objectClass=user)(|(displayname=$value)(samaccountname=$value))(!(userAccountControl:1.2.840.113556.1.4.803:=2))(!(memberof=$agemenegebruikersdn))(memberof:1.2.840.113556.1.4.1941:=$groupdn))";
		}
		else {
			$filter = "(&(objectCategory=user)(objectClass=user)(|(displayname=$value)(samaccountname=$value))(!(userAccountControl:1.2.840.113556.1.4.803:=2))(memberof:1.2.840.113556.1.4.1941:=$groupdn))";
		}

		$filter2 = "(&(objectCategory=user)(objectClass=user)(|(displayname=$value)(samaccountname=$value))(!(userAccountControl:1.2.840.113556.1.4.803:=2))(memberof:1.2.840.113556.1.4.1941:=$groupdn))";

		$result = ldap_search( $this->con , $this->dn , $filter , $this->attributes );

		if( ldap_errno( $this->con ) )
			throw new searchException( 'Unable to conduct search: ' . ldap_error($this->con ) );


		$result2 = ldap_search( $this->con2 , $this->dn2 , $filter2 , $this->attributes );

		if( ldap_errno( $this->con2 ) )
			throw new searchException( 'Unable to conduct search: ' . ldap_error($this->con2 ) );


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
					$object = $this->fillObject($rows[ $index ], false );
					$output[] = $object;
				}
			}

			if( ldap_count_entries($this->con2 , $result2 ) )
			{
			    ldap_sort($this->con2 , $result2 , 'samaccountname');
				$i=0;
				$entry = ldap_first_entry($this->con2, $result2);
				do {
					$attributes = ldap_get_attributes($this->con2, $entry);
					for($j=0; $j<$attributes['count']; $j++)
					{
						$values = ldap_get_values_len($this->con2, $entry,$attributes[$j]);

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
				while ($entry = ldap_next_entry($this->con2, $entry));

				$rows[ 'count' ] = ldap_count_entries( $this->con2 , $result2 );

				for( $index = 0 ; $index < $rows[ 'count' ] ; $index++ )
				{
					$object = $this->fillObject($rows[ $index ], false );
					$output[] = $object;
				}
			}

		return $output;
	}


/**
     * This function will return a object filled with the data contained in the array given as parameter
     *
     *
     * @param array $data is een array van gegevens waarin de gegevens moeten worden getransformeerd van
     * de database naar data-attributen.
     * @return Object
     * @throws classException
     */
	protected  function fillObject($data,$resolvegroups = true ){
		$object = parent::fillObject($data);

		//	resolve groups
		if($resolvegroups){
			$groupmodel = new groupModel($this->domain);
			$groups = array();
			$objectmemberof = $object->getMemberof();
			if(is_array($objectmemberof)){
				foreach ($object->getMemberof() as $groupdn){
					$group = $groupmodel->getfromDn($groupdn);
					if(count($group) == 1){
						$group = $group[0];

						if(!isset($groups[$group->getId()])){
							$groups[$group->getId()] = $group->getName();


							//	Nested group support
							$memberofs = $group->getMemberof();
							if($memberofs != ''){
								if(is_array($memberofs)){
									foreach($memberofs as $memberof){
										$this->resolvenestedgroupmembership($groups, $memberof);
									}
								}
								else {
									$this->resolvenestedgroupmembership($groups, $memberofs);
								}
							}
						}
					}
				}
			}else {
				$group = $groupmodel->getfromDn($objectmemberof);
				if(count($group) == 1){
					$group = $group[0];

					if(!isset($groups[$group->getId()])){
						$groups[$group->getId()] = $group->getName();


						//	Nested group support
						$memberofs = $group->getMemberof();
						if($memberofs != ''){
							if(is_array($memberofs)){
								foreach($memberofs as $memberof){
									$this->resolvenestedgroupmembership($groups, $memberof);
								}
							}
							else {
								$this->resolvenestedgroupmembership($groups, $memberofs);
							}
						}
					}
				}
			}

			$object->setGroupid(array_flip($groups));
		}


		return $object;
	}

	public function resolvenestedgroupmembership(&$groups, $memberof){
		$groupmodel = new groupModel($this->domain);
		$group = $groupmodel->getfromDn($memberof);
		if(count($group) == 1){
			$group = $group[0];

			if(!isset($groups[$group->getId()])){
				$groups[$group->getId()] = $group->getName();

				//	Nested group support
				$memberofs = $group->getMemberof();
				if($memberofs != ''){
					if(is_array($memberofs)){
						foreach($memberofs as $memberof){
							$this->resolvenestedgroupmembership($groups, $memberof);
						}
					}
					else {
						$this->resolvenestedgroupmembership($groups, $memberofs);
					}
				}
			}
		}
	}

	public function auth($username,$password){
		require(FRAMEWORK . DS . 'conf' . DS . 'datastore.php');


		$config = $datastore[$this->datastore];

		$con = @ldap_connect( $config['protocol'] . $config['domain'] );

		if(!$con){
			throw new connectException('Could not connect to the Active Directory.');
		}

		ldap_set_option($con , LDAP_OPT_REFERRALS , 0 );
		ldap_set_option($con , LDAP_OPT_PROTOCOL_VERSION , 3 );

		if (!@ldap_bind( $con , $username . '@' . $config['domain'] , $password )){
			$config2 = $datastore[$this->datastore2];

			$con2 = @ldap_connect( $config2['protocol'] . $config2['domain'] );
			ldap_set_option($con2 , LDAP_OPT_REFERRALS , 0 );
			ldap_set_option($con2 , LDAP_OPT_PROTOCOL_VERSION , 3 );

			if(!$con2){
				throw new connectException('Could not connect to the Active Directory.');
			}

			if(!@ldap_bind( $con2 , $username . '@' . $config2['domain'] , $password )){
				return false;
			}
			else {
				$model = new userModel();
				$users = $model->getfromUsername($username);

				if(count($users) == 1){
					return $users[0];
				}
				else {
					return false;
				}
			}
		}
		else {
			$model = new userModel();
			$users = $model->getfromUsername($username);

			if(count($users) == 1){
				return $users[0];
			}
			else {
				return false;
			}
		}
	}
}

?>