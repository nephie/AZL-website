<?php

class mbprocessedlogModel extends mblogModel
{
	protected $list = array('14552374690' => 'Dokter X' , '10784024410' => 'Dokter Y');

	protected  function fillObject($data){
		$object = parent::fillObject($data);

		if(isset($this->list[$object->getSender()])){
			$object->setSender($this->list[$object->getSender()]);
		}

		return $object;
	}

	protected function getClause($conditions){

		$conditions = $this->filterconditions($conditions);

		return parent::getClause($conditions);
	}

	protected function filterconditions($conditions){
		$newconditions = array();
		foreach($conditions as $key => $condition){
			if(!isset($condition['value'])){
				$newconditions[$key] = $this->filterconditions($condition);
            }
            else {
				if($key == 'sender'){
					$revlist = array_flip($this->list);
					$value = substr($condition['value'],1,strlen($condition['value']) - 2 );
					if(isset($revlist[$value])){
						$condition['value'] = $revlist[$value];
						$newconditions[$key] = $condition;
					}
					else {
						$newconditions[$key] = $condition;
					}
				}
				else {
					$newconditions[$key] = $condition;
				}
            }
		}

		return $newconditions;
	}
}

?>