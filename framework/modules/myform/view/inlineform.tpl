{if $form instanceof form}
<span  class="inlineform">
<form onsubmit="return false;" id="{$form->getId()}">
<span class="formfields">
{foreach from=$form->getField() item=field}
	{if ! $field instanceof hiddenField}
		{include file="formField.tpl" field=$field}{include file="formfieldvalidateerror.tpl" validateerror=$field-getValidateerror()}
	{else}
		{include file="formField.tpl" field=$field}
	{/if}
{/foreach}
</span>
<span class="formbuttons"><input type="submit" value="{$form->getSubmittext()}" onclick="{ajaxform form=$form}" /></span>
<input type="hidden" id="hidden_form_id" name="hidden_form_id" value="{$form->getId()}" />
</form>
</span>
{else}
meh
{/if}