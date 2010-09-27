{if $form instanceof form}
<form onsubmit="return false;" id="{$form->getId()}">
	<div class="formfields">
		<table>
		{foreach from=$form->getField() item=field}
		{if ! $field instanceof hiddenField}
			<tr>
				<td class="formlabel">{include file="formfieldlabel.tpl" field=$field}</td>
				<td class="formfield">{include file="formField.tpl" field=$field}</td>

			</tr>
		{else}
			{include file="formField.tpl" field=$field}
		{/if}
		{/foreach}
		<tr>
			<td>&nbsp;</td>
			<td>

				<div class="formbuttons"><input type="submit" value="{$form->getSubmittext()}" onclick="tinyMCE.triggerSave(false, false);{ajaxform form=$form}" /> <input type="reset" value="{$form->getResettext()}" /></div>


				<input type="hidden" id="hidden_form_id" name="hidden_form_id" value="{$form->getId()}" />
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<div class="formerror" id="formerror_{$form->getId()}">&nbsp;</div>
			</td>
		</tr>
		</table>
		</div>
</form>
{$form->getFocus()}
{/if}