<?php
	class myarticleversionObject extends object {
		protected $id;

		protected $articleid;

		protected $title;
		protected $content;
		protected $author;
		protected $authorname;
		protected $creationdate;
		protected $state;
		protected $startpublishdate;
		protected $stoppublishdate;

		public function getWikicontent($section,$self){
			$content = $this->content;

			$matches = array();
			preg_match_all('/\[\[([^\[\]]+)\]\]/',$content,$matches);

			$matches2 = array();
			preg_match_all('/\[([^\[\]]+)\[([^\[\]]+)\]\]/',$content,$matches2);

			for($i = 0; $i < count($matches[0]); $i++){
				$alias = addslashes($matches[1][$i]);
				$content = str_replace($matches[0][$i],'<a href="#" onclick="xajax_dispatch( \'' . $self . '\' , \'myarticle\' , \'followwikilink\' , \'name:' . $alias . '\', \'title:' . $alias . '\', \'section:' . $section . '\' , \'curarticle:' . $this->articleid . '\');return false;">' . stripslashes($alias) . '</a>',$content);
			}

			for($i = 0; $i < count($matches2[0]); $i++){
				$alias = addslashes($matches2[1][$i]);
				$pretty = addslashes($matches2[2][$i]);
				$content = str_replace($matches2[0][$i],'<a href="#" onclick="xajax_dispatch( \'' . $self . '\' , \'myarticle\' , \'followwikilink\' , \'name:' . $alias . '\', \'title:' . $pretty . '\', \'section:' . $section . '\' , \'curarticle:' . $this->articleid . '\');return false;">' . stripslashes($pretty) . '</a>',$content);
			}

			//$content = preg_replace('/\[\[([^\[\]]+)\]\]/','<a href="#" onclick="xajax_dispatch( \'' . $self . '\' , \'myarticle\' , \'followwikilink\' , \'name:$1\', \'title:$1\', \'section:' . $section . '\' , \'curarticle:' . $this->articleid . '\');return false;">$1</a>',$content);
			//$content = preg_replace('/\[([^\[\]]+)\[([^\[\]]+)\]\]/','<a href="#" onclick="xajax_dispatch( \'' . $self . '\' , \'myarticle\' , \'followwikilink\' , \'name:$1\', \'title:$2\', \'section:' . $section . '\' , \'curarticle:' . $this->articleid . '\');return false;">$2</a>',$content);

			return $content;
		}


	}
?>