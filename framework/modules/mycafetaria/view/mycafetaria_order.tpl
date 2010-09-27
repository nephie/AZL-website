<h1>Bestellen</h1>
<div class="headerline">&nbsp;</div>
<div style="position: relative;">
<div style=" font-weight: bold; font-size: xx-large; height: 50px; padding: 5px; float:right;clear: both;" id="orderprice">€{$price|string_format:"%.2f"}</div>
</div>
<p>
<strong>Opgelet: Het is niet meer toegestaan om opmerkingen toe te voegen naast uw naam!</strong> Als u dit wel doet zal er geen rekening mee gehouden worden
in de keuken/cafetaria en zal je heel moeilijk tot niet kunnen nakijken of je al iets besteld hebt.
</p>
<p>
<strong>De hier getoonde prijzen zijn alleen geldig voor personeelsleden.</strong>
</p>
<p>
<table>
	<tr>
		<td>
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
{$form->getFocus()}
{/if}
		</td>
		<td>
			<div id="extraordercontainer"></div>
		</td>
	</tr>
</table>
</p>