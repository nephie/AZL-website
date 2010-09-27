<?php
class ftgdallowedModel extends mssqlmodel {
	protected $datastore = 'fortiguardlogs';

	protected $mapping = array(
		'id' => 'id',
		'logid' => 'logid',
		'time' => 'time',
		'user' => 'user',
		'group' => 'group',
		'sourceip' => 'sourceip',
		'destip' => 'destip',
		'host' => 'host',
		'url' => 'url',
		'cat' => 'cat'
	);

	protected function fillObject($data,$noassoc){
		$object = parent::fillObject($data,$noassoc);

		$host = '<a href="http://' . $object->getHost() . '" target="_blank">Host</a>';
		$url = '<a href="http://' . $object->getHost() . $object->getUrl() . '" target="_blank">Url</a>';

		$object->setGoto($host . ' | ' . $url);

		return $object;
	}
}
?>