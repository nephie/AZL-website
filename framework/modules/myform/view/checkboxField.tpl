{if $field->isSelected()}
		{assign var=checked value='checked="checked"'}
{else}
		{assign var=checked value=''}
{/if}
<input class="checkbox" title="{$field->getExtra()}" type="checkbox" name="{$field->getName()}" id="{$field->getId()}"  value="{$field->getDefaultvalue()}" {$checked} onEnter="{ajaxform form=$form}" {if $form->isPhased()} onchange="{ajaxform form=$form notfinal=true field=$field->getName()}" onclick="this.blur();" {/if}/>