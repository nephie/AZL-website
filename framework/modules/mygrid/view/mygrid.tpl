{if $grid instanceof mygrid}

<div class="gridcontainer" id="{$grid->getId()}">
	{include file="mygridstructure.tpl"}
</div>
<br />
<br />
<div class="gridextra" style="position:relative;" id="gridextra_{$grid->getId()}">

</div>

{/if}