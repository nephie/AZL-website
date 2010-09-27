{if $field->getValue() != ''}
	{assign var=fieldValue value=$field->getValue()}
{else}
	{assign var=fieldValue value=$field->getDefaultvalue()}
{/if}
	{assign var=class value="timepicker"}
<input type="text" title="{$field->getExtra()}" class="{$class}" name="{$field->getName()}" id="{$field->getId()}" value="{$fieldValue}" onEnter="{ajaxform form=$form}" />
{$field->loaddatepicker()}