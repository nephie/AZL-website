{if $field->getValue2() != ''}
	{assign var=fieldValue2 value=$field->getValue2()}
{else}
	{assign var=fieldValue2 value=$field->getDefaultvalue()}
{/if}

<input autocomplete="off" type="text" title="{$field->getExtra()}" name="{$field->getName()}_text[]" id="{$field->getId()}_text" value="{$fieldValue2}" onFocus="suggestselect_handlefocusin(this, '{$field->getCallbackcontroller()}', '{$field->getCallbackfunction()}', '{extraparamlist field=$field}');" onBlur="suggest_handlefocusout(this);" onKeyPress="return nosubmitonenter(event,this)" onKeyUp="suggestselect_handlekeyup(event, this, '{$field->getCallbackcontroller()}', '{$field->getCallbackfunction()}', '{extraparamlist field=$field}');" />
<br/>
<br />

{if $field->getMultiple()}
	{assign var=multiple value='multiple="multiple"'}
	{assign var=class value='class="multipleSelect"'}
{else}
	{assign var=multiple value=""}
	{assign var=class value=""}
{/if}
<div id="{$field->getId()}_container">
{include file="selectField.tpl" field=$field}
</div>