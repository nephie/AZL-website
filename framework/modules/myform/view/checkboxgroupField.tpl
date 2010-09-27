{foreach from=$field->getBoxes() key=id item=box}
{if $box.selected}
		{assign var=checked value='checked="checked"'}
{else}
		{assign var=checked value=''}
{/if}
<input class="checkbox" title="{$field->getExtra()}" type="checkbox" name="{$field->getName()}[]"   value="{$id}" {$checked} onEnter="{ajaxform form=$form}" {if $form->isPhased()} onChange="{ajaxform form=$form notfinal=true field=$field->getName()}" onclick="this.blur();"{/if}/>&nbsp;{$box.name}&nbsp;&nbsp;&nbsp;&nbsp;
{/foreach}