{assign var=options value=$field->getOption()}

{if $field->getMultiple()}
<ul>
	{section name=selectloop loop=$field->getIndex()}
		{if isset($options[$smarty.section.selectloop.index])}
			{assign var=option value=$options[$smarty.section.selectloop.index]}
			{if $option->getSelected()}
				<li>{$option->getName()}</li>
			{/if}

		{/if}
	{/section}
</ul>
{else}
	{section name=selectloop loop=$field->getIndex()}
		{if isset($options[$smarty.section.selectloop.index])}
			{assign var=option value=$options[$smarty.section.selectloop.index]}
			{if $option->getSelected()}
				{$option->getName()}
			{/if}

		{/if}
	{/section}
{/if}

	{include file="hiddenField.tpl" field=$field}