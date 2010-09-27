{if $form instanceof form}
{if $title <> ''}
<h1>{$title}</h1>
<div class="headerline">&nbsp;</div>
{/if}
<form onsubmit="return false;" id="{$form->getId()}">
	<div class="formfields">
		<table>
		{foreach from=$form->getField() item=field}
		{if ! $field instanceof hiddenField}
			<tr>
				<td class="formlabel"><strong>{include file="formfieldlabel.tpl" field=$field}</strong></td>
				<td class="formfield">{include file="confirmformField.tpl" field=$field}</td>
			</tr>
		{else}
			{include file="formField.tpl" field=$field}
		{/if}
		{/foreach}
		<tr>
			<td>&nbsp;</td>
			<td>
				{if !$form->isPhased() || $form->isReady()}
				<div class="formbuttons"><input type="submit" value="{$form->getSubmittext()}" onclick="tinyMCE.triggerSave(false, false);{ajaxform form=$form}" /> <input type="reset" value="Wijzigen" onclick="tinyMCE.triggerSave(false, false);{ajaxform abort='true' form=$form}" /></div>
				{elseif $form->isPhased() && !$form->isReady()}
				<div class="formbuttons"><input type="submit" value="{$form->getSubmittext()}" onclick="" /><input type="reset" value="Wijzigen" onclick="tinyMCE.triggerSave(false, false);{ajaxform abort='true' form=$form}" /></div>
				{/if}

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
{/if}