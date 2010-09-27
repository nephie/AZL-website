{foreach from=$result item=item name=results}
<div id="suggestresults_{$id}_{$smarty.foreach.results.index}" class="suggestresultitem" onMouseOver="suggest_handlemouseover('{$id}', {$smarty.foreach.results.index});" onClick="suggest_fillfield('{$id}','{$item|rawurlencode}')">{$item}</div>
{/foreach}