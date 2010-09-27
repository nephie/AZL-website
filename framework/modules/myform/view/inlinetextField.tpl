{if $field->getValue() != ''}
	{assign var=fieldValue value=$field->getValue()}
	{assign var=clear value=false}
{elseif $field->getDefaultvalue() != ''}
	{assign var=fieldValue value=$field->getDefaultvalue()}
	{assign var=clear value=false}
{else}
	{assign var=fieldValue value=$field->getLabel()}
	{assign var=clear value=true}
{/if}
<input type="text" title="{$field->getExtra()}" name="{$field->getName()}" id="{$field->getId()}" value="{$fieldValue}" onKeydown="{literal}if(window.event.keyCode == 13){{/literal}{ajaxform form=$form}{literal}};{/literal}"
{if $clear}
	onFocus="if (this.value == '{$fieldValue}') {literal}{{/literal} this.value = ''; {literal}}{/literal}" onBlur="if (this.value == '') {literal}{{/literal} this.value = '{$fieldValue}'; {literal}}{/literal}"
{/if}
/>
