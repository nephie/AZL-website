<h1>Inloggen</h1>
<div class="headerline">&nbsp;</div>
<p>
Geef hier uw persoonlijke gebruikersnaam en wachtwoord in om toegang te krijgen tot het beveiligde gedeelte van de site.
<br />
Indien u niet over deze gegevens beschikt, kan u de informatica-dienst van het AZ Lokeren hierover contacteren.
</p>
<p>
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
				<div class="formbuttons"><input type="submit" value="{$form->getSubmittext()}" onclick="{ajaxform form=$form}" /> </div>
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
</p>