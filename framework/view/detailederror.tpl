<strong>{$exception->getMessage()}</strong> in <strong>{$exception->getFile()}</strong> on line <strong>{$exception->getLine()}</strong> <div id="show{$exceptionid}">(<a href ="javascript:;" onclick="xajax.dom.assign('{$exceptionid}' , 'style.display' , 'block');xajax.dom.assign('show{$exceptionid}' , 'style.display' , 'none');">show trace</a>)</div>
<div id="{$exceptionid}" style="display:none;">(<a href="javascript:;" onclick="xajax.dom.assign('{$exceptionid}' , 'style.display' , 'none');xajax.dom.assign('show{$exceptionid}' , 'style.display' , 'block');">Hide trace</a>)<pre>{$exception->getTrace()|@print_r}</pre></div>