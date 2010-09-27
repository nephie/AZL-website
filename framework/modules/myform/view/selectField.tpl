{if $field->getMultiple()}
	{assign var=multiple value='multiple="multiple"'}
	{assign var=class value='class="multipleSelect"'}
{else}
	{assign var=multiple value=""}
	{assign var=class value=""}
{/if}
<select name="{$field->getName()}{if $field->getMultiple()}[]{/if}" title="{$field->getExtra()}"  id="{$field->getId()}" {$multiple} {$class} {if $form->isPhased()} onChange="{ajaxform form=$form notfinal=true field=$field->getName()}"{/if}>
	{assign var=options value=$field->getOption()}
	{assign var=optgroups value=$field->getOptgroup()}

	{section name=selectloop loop=$field->getIndex()}
		{if isset($options[$smarty.section.selectloop.index])}
			{assign var=option value=$options[$smarty.section.selectloop.index]}
			{if $option->getSelected()}
				{assign var=selected value='selected="selected"'}
			{else}
				{assign var=selected value=""}
			{/if}
			<option class="tips" label="{$option->getName()}" title="{$option->getExtra()}" value="{$option->getValue()}" {$selected}>{$option->getName()}</option>
		{/if}

		{if isset($optgroups[$smarty.section.selectloop.index])}
			{assign var=optgroup value=$optgroups[$smarty.section.selectloop.index]}
			<optgroup label="{$optgroup->getName()}">
				{foreach from=$optgroup->getOption() item=option}
					{if $option->getSelected()}
						{assign var=selected value='selected="selected"'}
					{else}
						{assign var=selected value=""}
					{/if}
					<option label="{$option->getName()}" value="{$option->getValue()}" {$selected}>{$option->getName()}</option>
				{/foreach}
			</optgroup>
		{/if}
	{/section}
</select>