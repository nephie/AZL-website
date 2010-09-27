{foreach from=$field->getBoxes() key=id item=box}
<strong>{$box.name}:</strong>
{if $box.selected}
		Ja
		<input type="hidden" name="{$field->getName()}[]" value="{$id}" />
{else}
		Nee

{/if}

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
{/foreach}