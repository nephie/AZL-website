{if $field->getValue() != ''}
	{assign var=fieldValue value=$field->getValue()}
{else}
	{assign var=fieldValue value=$field->getDefaultvalue()}
{/if}
{if $field->getTime()}
	{assign var=class value="datepicker_time"}
{else}
	{assign var=class value="datepicker"}
{/if}
<input type="text" title="{$field->getExtra()}" class="{$class}" name="{$field->getName()}" id="{$field->getId()}" value="{$fieldValue}" onEnter="{ajaxform form=$form}" />
{$field->loaddatepicker()}