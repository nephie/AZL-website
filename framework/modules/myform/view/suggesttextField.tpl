{if $field->getValue() != ''}
	{assign var=fieldValue value=$field->getValue()}
{else}
	{assign var=fieldValue value=$field->getDefaultvalue()}
{/if}
<input autocomplete="off" type="text" title="{$field->getExtra()}" name="{$field->getName()}" id="{$field->getId()}" value="{$fieldValue}" onFocus="suggest_handlefocusin(this, '{$field->getCallbackcontroller()}', '{$field->getCallbackfunction()}');" onKeyPress="return nosubmitonenter(event,this)" onKeyUp="suggest_handlekeyup(event, this, '{$field->getCallbackcontroller()}', '{$field->getCallbackfunction()}')" />
<div style="display:none;" class="suggestresult" id="{$field->getId()}_result"></div>