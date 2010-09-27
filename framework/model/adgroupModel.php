<?php

class groupModel extends admodel {

	protected $mapping = array('objectguid' => 'id' , 'name' => 'name' ,  'description' => 'description' , 'memberof' => 'memberof' , 'member' => 'member', 'mail' => 'mail', 'displayname' => 'displayname');


	public function getfromName( $name , $noassoc = false ){
		$name = $this->adescape($name);
		$filter = "(&(objectCategory=group)(samaccountname=$name))";
		$result = ldap_search( $this->con , $this->dn , $filter , $this->attributes );

		if( ldap_errno( $this->con ) )
			throw new searchException( 'Unable to conduct search: ' . ldap_error($this->con ) );


		$result2 = ldap_search($this->con2,$this->dn2,$filter,$this->attributes);


		if( ldap_errno( $this->con2 ) )
			throw new searchException( 'Unable to conduct search: ' . ldap_error($this->con2 ) );

		$rs1 = $this->parseResult($result , $noassoc);
		$rs2 = $this->parseResult($result2 , $noassoc);

		return array_merge($rs1,$rs2);
	}

	public function getfromDn( $dn , $noassoc = false ){
		$dn = $this->adescape($dn);
		$filter = "(&(objectCategory=group)(distinguishedname=$dn))";
		$result = ldap_search( $this->con , $this->dn , $filter , $this->attributes );

		if( ldap_errno( $this->con ) )
			throw new searchException( 'Unable to conduct search: ' . ldap_error($this->con ) );

		if(ldap_count_entries($this->con,$result) == 0){
			$result = ldap_search($this->con2,$this->dn2,$filter,$this->attributes);
		}

		if( ldap_errno( $this->con2 ) )
			throw new searchException( 'Unable to conduct search: ' . ldap_error($this->con2 ) );

		return $this->parseResult($result , $noassoc);
	}


}

?>