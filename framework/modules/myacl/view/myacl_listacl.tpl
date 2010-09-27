{if $showrequester}
	{assign var=requester value="'Aanvrager' => 'requester',"}
{else}
	{assign var=requester value=""}
{/if}
{if $showobject}
	{assign var=object value="'Item' => 'object',"}
{else}
	{assign var=object value=""}
{/if}
{include file="mygrid.tpl" grid=$acllist columns="array($requester$object'Recht' => 'rightdesc')"}