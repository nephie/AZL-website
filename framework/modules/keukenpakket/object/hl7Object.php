<?php
class hl7Object extends object {

	protected $data;
	protected $datastring;

	protected $sourcepath;

	protected $fieldseperator1 = '|';
	protected $fieldseperator2 = '^';

	public function getFieldvalue($segment,$fieldnr){
		$segment = strtoupper($segment);
		return (isset($this->data[$segment][$fieldnr])) ? $this->data[$segment][$fieldnr] : false;
	}

	public function changetype($newtype){
		$this->data['EVN'][1] = $newtype;
	}

	public function importData($datastring){
		$this->datastring = $datastring;

		$segments = explode("\r",$this->datastring);
		$empty = array_pop($segments);

		foreach($segments as $segment){
			$segment = str_replace("\r",'',$segment);
			$segment = str_replace("\n",'',$segment);
			$fields = explode($this->fieldseperator1,$segment);

			$segment = $fields[0];
			if(strtoupper($segment) == 'MSH'){
				array_unshift($fields,$segment);
				$fields[1] = $this->fieldseperator1;
			}

			foreach($fields as $field){
				$this->data[strtoupper($segment)][] = $field;
			}
		}
	}
}
?>