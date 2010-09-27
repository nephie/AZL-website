<?php

class popupController extends controller  {

	public function create($parameters){
		$name = $parameters['name'];
		$content = $parameters['content'];
		$title = $parameters['title'];

		$template = new ui($this);

		$destroyRequest = new ajaxrequest('popup','destroy');

		$template->assign('name' , $name);
		$template->assign('content' , $content);
		$template->assign('destroyRequest' , $destroyRequest);
		$template->assign('title',$title);


		$content = $template->fetch('popup.tpl');
		$content = addslashes($content);
		$content = str_replace('\\"','"',$content);

		$content = preg_replace('/[\r\n]+/', "", $content);

		$script = "hs.htmlExpand(null , { maincontentText: '<div style=\"height:200px;\" class=\"popup\">$content</div>'})";

		$this->response->script($script);
	}

	public function destroy($parameters){
		$this->response->script("hs.close()");
		$this->response->remove($parameters['name']);
	}

	public function createflash($parameters){
		$name = $parameters['name'] . uniqid();
		$content = $parameters['content'];

		if(isset($parameters['duration'])) {
			$duration = $parameters['duration'];
		}
		elseif($parameters['type'] == 'success'){
			$duration = 5000;
		}
		elseif($parameters['type'] == 'warning'){
			$duration = 8000;
		}
		elseif($parameters['type'] == 'error'){
			$duration = -1;
		}

		$destroyRequest = new ajaxrequest('popup' , 'destroy' , array('name' => $name));

		$template = new ui($this);

		$template->assign('name' , $name);
		$template->assign('content' , $content);
		$template->assign('destroyRequest' , $destroyRequest);
		$template->assign('type',$parameters['type']);

		$this->response->assign('flashcontainer' , 'innerHTML' , $template->fetch('flash.tpl'));
		if($duration != -1){
			$this->response->script("setTimeout(\"$('$name').fade('out')\",$duration);setTimeout(\"$('$name').dispose()\"," . ($duration + 300) . ");");
		}
	}
}

?>