<?php
class myarticleversionModel extends mssqlmodel {
	protected $mapping = array('id' => 'id','articleid' => 'articleid', 'title' => 'title', 'content' => 'content', 'author' => 'author', 'authorname' => 'authorname' , 'state' => 'state', 'creationdate' => 'creationdate', 'startpublishdate' => 'startpublishdate' , 'stoppublishdate' => 'stoppublishdate');
}
?>