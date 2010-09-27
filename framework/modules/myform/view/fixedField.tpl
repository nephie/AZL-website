{if $field->getValue() != ''}
	{assign var=fieldValue value=$field->getValue()}
{else}
	{assign var=fieldValue value=$field->getDefaultvalue()}
{/if}
{$fieldValue}
