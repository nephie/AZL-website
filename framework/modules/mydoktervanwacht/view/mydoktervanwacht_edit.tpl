<div style="position: relative">
<h1>Dokter van wacht: {$specialisme->getName()}</h1>
<div style="position: absolute; right: 0px; top: 0px;"><a href="#" onClick="{ajaxrequest request=$closerequest}">Sluiten</a></div>
<div class="headerline">&nbsp;</div>
</div>

<p>
	{if $form instanceof form}
	<form onsubmit="return false;" id="{$form->getId()}">
	<div class="formfields">
		<table>
		{foreach from=$form->getField() item=field}
		{if ! $field instanceof hiddenField}
			{if $field->getName() != 'day'}
			<tr>
				<td class="formlabel">{include file="formfieldlabel.tpl" field=$field}</td>
				<td class="formfield">{include file="formField.tpl" field=$field}</td>

			</tr>
			{/if}
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

{$form->getFocus()}
{/if}
</p>
<p>
	<table class="grid">
		<tr class="gridhead">
			<td style="vertical-align: middle; width: 50px; text-align: left; position: relative;" class="gridadd">
					
				</a>
			</td>
			<td>
				Datum
			</td>
			
			<td>
				<table>
				
					<tr>
						<td style="width: 50px;">
							&nbsp;
						</td>
						<td style="width: 75px;">
							Van
						</td>
						<td style="width: 75px;">
							Tot
						</td>
						<td>
							Dokter
						</td>
					</tr>
				
				</table>
			</td>
		</tr>
		{foreach from=$list item=day}
		{cycle values="gridrow_A,gridrow_B" assign=rowcycle}
		<tr class="gridrow {$rowcycle}">
			<td>
				
			</td>
			<td>
				{$day.start|date_format:"%d/%m/%Y"}
			</td>
			<td>
				<table>
				{foreach from=$day.dokters item=dokter}
					<tr>
						<td style="width: 50px;">
							
						</td>
						<td style="width: 75px;">
							{$dokter->getStart()|date_format:"%H:%M"}
						</td>
						<td style="width: 75px;">
							{$dokter->getStop()|date_format:"%H:%M"}
						</td>
						<td>
							Dr. {$dokter->getNaam()} {$dokter->getVoornaam()}
						</td>
					</tr>
				{/foreach}
				</table>
			</td>
		</tr>
		{/foreach}
		<tr class="gridfoot">
			<td colspan="999">
				
			</td>
		</tr>
	</table>
</form>
</p>