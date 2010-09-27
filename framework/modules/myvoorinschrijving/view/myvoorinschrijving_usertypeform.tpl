{if $form instanceof form}
<form onsubmit="return false;" id="{$form->getId()}">
	<div class="formfields">
		<table>
		{foreach from=$form->getField() item=field}
		{if ! $field instanceof hiddenField}
			<tr>
				<td>
{if $field->getMultiple()}
	{assign var=multiple value='multiple="multiple"'}
	{assign var=class value='class="multpleSelect"'}
{else}
	{assign var=multiple value=""}
	{assign var=class value=""}
{/if}
<select name="{$field->getName()}"  id="{$field->getId()}" {$multiple} {$class} onChange="{ajaxform form=$form}">
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
			<option label="{$option->getName()}" value="{$option->getValue()}" {$selected}>{$option->getName()}</option>
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

				</td>
			</tr>
		{else}
			{include file="formField.tpl" field=$field}
		{/if}
		{/foreach}
		</table>
		</div>
		<input type="hidden" id="hidden_form_id" name="hidden_form_id" value="{$form->getId()}" />
</form>
{/if}