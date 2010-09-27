{if $field instanceof formField}
	{capture assign=template}{php}echo get_class($this->_tpl_vars['field']) . '.tpl';{/php}{/capture}

	{include file=$template field=$field}

{/if}