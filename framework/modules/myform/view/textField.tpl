{if $field->getValue() != ''}
	{assign var=fieldValue value=$field->getValue()}
{else}
	{assign var=fieldValue value=$field->getDefaultvalue()}
{/if}
<input type="text" title="{$field->getExtra()}" name="{$field->getName()}" id="{$field->getId()}" value="{$fieldValue}" onEnter="{ajaxform form=$form}" />
