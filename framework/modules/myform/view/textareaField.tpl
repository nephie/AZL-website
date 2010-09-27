{if $field->getValue() != ''}
	{assign var=fieldValue value=$field->getValue()}
{else}
	{assign var=fieldValue value=$field->getDefaultvalue()}
{/if}
<textarea name="{$field->getName()}" title="{$field->getExtra()}" id="{$field->getId()}">{$fieldValue}</textarea>